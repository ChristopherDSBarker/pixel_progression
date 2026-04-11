// ------------------
// CANVAS SETUP
// ------------------
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

let gameState = 'PLAYING';
let lastTime = performance.now();

let candies = 0;
let timeSinceLastCandy = 0;

// ------------------
// PLAYER
// ------------------
let player = {
    x: 50, y: 350,
    width: 20, height: 20,
    vx: 0, vy: 0,
    speed: 150,
    jumpHeight: 300,
    health: 240,
    level: 1,
    grounded: false
};

let gravity = 800;

// ------------------
// LEVEL (VERTICAL PLATFORMS)
// ------------------
let obstacles = [
    { x: 0, y: 380, width: 800, height: 20 },   // ground
    { x: 100, y: 320, width: 120, height: 20 },
    { x: 300, y: 260, width: 120, height: 20 },

    // 🔥 MOVING PLATFORM (FIXED UNREACHABLE PLATFORM)
    { 
        x: 150, 
        y: 200, 
        width: 120, 
        height: 20,
        moving: true,
        minX: 100,
        maxX: 500,
        direction: 1,
        speed: 120
    },

    { x: 400, y: 140, width: 120, height: 20 },
    { x: 250, y: 80,  width: 120, height: 20 }
];

// ------------------
// GOAL (DIAMOND SHAPE)
// ------------------
let star = {
    x: 280,
    y: 40,
    width: 20,
    height: 20
};

// ------------------
// INPUT
// ------------------
let keys = {};
window.addEventListener('keydown', e => keys[e.code] = true);
window.addEventListener('keyup', e => keys[e.code] = false);

// ------------------
// LOOP
// ------------------
function gameLoop(currentTime) {
    let deltaTime = (currentTime - lastTime) / 1000;
    if (deltaTime > 0.1) deltaTime = 0.1;
    lastTime = currentTime;

    // passive candy system
    timeSinceLastCandy += deltaTime;
    if (timeSinceLastCandy >= 1.0) {
        candies += 1;
        timeSinceLastCandy = 0;
        updateUI();
    }

    if (gameState === 'PLAYING') {
        updatePhysics(deltaTime);
        drawCanvas();
    }

    requestAnimationFrame(gameLoop);
}

// ------------------
// PHYSICS
// ------------------
function updatePhysics(deltaTime) {

    // movement
    if (keys['ArrowRight']) player.vx = player.speed;
    else if (keys['ArrowLeft']) player.vx = -player.speed;
    else player.vx = 0;

    player.x += player.vx * deltaTime;

    // gravity
    player.vy += gravity * deltaTime;
    player.y += player.vy * deltaTime;

    player.grounded = false;

    // ------------------
    // MOVE PLATFORMS
    // ------------------
    for (let obs of obstacles) {
        if (obs.moving) {
            obs.x += obs.direction * obs.speed * deltaTime;

            // bounce at bounds
            if (obs.x <= obs.minX) {
                obs.x = obs.minX;
                obs.direction = 1;
            } 
            else if (obs.x + obs.width >= obs.maxX) {
                obs.x = obs.maxX - obs.width;
                obs.direction = -1;
            }
        }
    }

    // ------------------
    // COLLISION
    // ------------------
    for (let obs of obstacles) {
        if (
            player.x < obs.x + obs.width &&
            player.x + player.width > obs.x &&
            player.y + player.height >= obs.y &&
            player.y + player.height <= obs.y + 10 &&
            player.vy >= 0
        ) {
            player.y = obs.y - player.height;
            player.vy = 0;
            player.grounded = true;

            // 🔥 PLAYER MOVES WITH PLATFORM (important polish)
            if (obs.moving) {
                player.x += obs.direction * obs.speed * deltaTime;
            }
        }
    }

    // jump
    if (keys['Space'] && player.grounded) {
        player.vy = -player.jumpHeight;
    }

    // ------------------
    // GOAL COLLISION (WIN)
    // ------------------
    if (
        player.x < star.x + star.width &&
        player.x + player.width > star.x &&
        player.y < star.y + star.height &&
        player.y + player.height > star.y
    ) {
        gameState = 'MENU';
        document.getElementById('levelStatus').style.display = 'block';
    }

    // prevent falling forever
    if (player.y > canvas.height + 100) {
        player.x = 50;
        player.y = 350;
        player.vy = 0;
    }
}

// ------------------
// DRAW
// ------------------
function drawCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // platforms
    ctx.fillStyle = '#888';
    for (let obs of obstacles) {
        ctx.fillRect(obs.x, obs.y, obs.width, obs.height);
    }

    // ------------------
    // DRAW GOAL (DIAMOND)
    // ------------------
    ctx.fillStyle = 'yellow';
    ctx.beginPath();
    ctx.moveTo(star.x + star.width / 2, star.y);
    ctx.lineTo(star.x + star.width, star.y + star.height / 2);
    ctx.lineTo(star.x + star.width / 2, star.y + star.height);
    ctx.lineTo(star.x, star.y + star.height / 2);
    ctx.closePath();
    ctx.fill();

    // player
    ctx.fillStyle = player.grounded ? '#39ff14' : '#00ffff';
    ctx.fillRect(player.x, player.y, player.width, player.height);
}

// ------------------
// UI
// ------------------
const resourceDisplay = document.getElementById('resourceDisplay');
const healthDisplay = document.getElementById('healthDisplay');
const upgradeButtons = document.getElementById('upgrade-buttons');
const storyText = document.getElementById('storyText');

function updateUI() {
    resourceDisplay.innerText = `Candies: ${candies}`;
    healthDisplay.innerText = `Health: ${player.health}`;

    if (candies >= 10 && gameState === 'MENU') {
        upgradeButtons.style.display = 'block';
        if (!storyText.innerText.includes("merchant")) {
            storyText.innerText = "The candy merchant appears and offers you upgrades...";
        }
    } else {
        upgradeButtons.style.display = 'none';
    }
}

// ------------------
// UPGRADES
// ------------------
document.getElementById('btnUpgradeHealth').addEventListener('click', () => applyUpgrade('health'));
document.getElementById('btnUpgradeJump').addEventListener('click', () => applyUpgrade('jump'));
document.getElementById('btnUpgradeSpeed').addEventListener('click', () => applyUpgrade('speed'));

function applyUpgrade(type) {
    if (candies >= 10) {
        candies -= 10;

        if (type === 'health') player.health += 20;
        if (type === 'jump') player.jumpHeight += 50;
        if (type === 'speed') player.speed += 50;

        player.x = 50;
        player.y = 350;

        document.getElementById('levelStatus').style.display = 'none';
        storyText.innerText = "";

        gameState = 'PLAYING';
        updateUI();
        saveGame();
    }
}

// ------------------
// SAVE
// ------------------
function saveGame() {
    const saveData = {
        playerId: "student_dev_1",
        health: player.health,
        candies: candies,
        level: player.level,
        stats: { jumpHeight: player.jumpHeight, speed: player.speed }
    };

    fetch('save.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(saveData)
    });
}

requestAnimationFrame(gameLoop);

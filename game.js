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
    x: 50, y: 300,
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
// LEVEL (VISIBLE PLATFORM)
// ------------------
let obstacles = [
    { x: 0, y: 380, width: 800, height: 20 }, // ground
    { x: 300, y: 300, width: 120, height: 20 } // platform
];

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

    // FIXED movement logic
    if (keys['ArrowRight']) player.vx = player.speed;
    else if (keys['ArrowLeft']) player.vx = -player.speed;
    else player.vx = 0;

    player.x += player.vx * deltaTime;

    // gravity
    player.vy += gravity * deltaTime;
    player.y += player.vy * deltaTime;

    player.grounded = false;

    // collision
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
        }
    }

    // jump
    if (keys['Space'] && player.grounded) {
        player.vy = -player.jumpHeight;
    }

    // level complete trigger
    if (player.x > canvas.width) {
        gameState = 'MENU';
        document.getElementById('levelStatus').style.display = 'block';
    }
}

// ------------------
// DRAW (NOW REAL GRAPHICS)
// ------------------
function drawCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // platforms
    ctx.fillStyle = '#888';
    for (let obs of obstacles) {
        ctx.fillRect(obs.x, obs.y, obs.width, obs.height);
    }

    // player (animated color change)
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

        // reset level
        player.x = 50;
        player.y = 300;

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

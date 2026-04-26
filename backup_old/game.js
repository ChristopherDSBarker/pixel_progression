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
// CAMERA (ADDED)
// ------------------
let cameraY = 0;

// ------------------
// LEVEL SYSTEM
// ------------------
let nextLevel = 1;

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
// LEVEL DATA
// ------------------
let obstacles = [];
let star = { x: 0, y: 0, width: 20, height: 20 };

// ------------------
// LOAD LEVELS
// ------------------
function loadLevel(level) {

    player.x = 50;
    player.y = 350;
    player.vx = 0;
    player.vy = 0;

    cameraY = 0; // reset camera

    if (level === 1) {
        obstacles = [
            { x: 0, y: 380, width: 800, height: 20 },
            { x: 100, y: 320, width: 120, height: 20 },
            { x: 300, y: 260, width: 120, height: 20 },
            { x: 150, y: 200, width: 120, height: 20, moving: true, minX: 100, maxX: 500, direction: 1, speed: 120 },
            { x: 400, y: 140, width: 120, height: 20 },
            { x: 250, y: 80,  width: 120, height: 20 }
        ];

        star = { x: 280, y: 40, width: 20, height: 20 };
    }

    if (level === 2) {
        obstacles = [
            { x: 0, y: 380, width: 800, height: 20 },

            { x: 100, y: 330, width: 120, height: 20 },
            { x: 250, y: 300, width: 120, height: 20 },
            { x: 120, y: 270, width: 120, height: 20 },
            { x: 300, y: 240, width: 120, height: 20 },

            { x: 180, y: 210, width: 120, height: 20 },
            { x: 340, y: 180, width: 120, height: 20 },
            { x: 140, y: 150, width: 120, height: 20 },
            { x: 280, y: 120, width: 120, height: 20 },

            { x: 160, y: 90, width: 120, height: 20, moving: true, minX: 100, maxX: 500, direction: 1, speed: 90 },
            { x: 320, y: 60, width: 120, height: 20, moving: true, minX: 150, maxX: 550, direction: -1, speed: 100 }
        ];

        star = { x: 260, y: 20, width: 20, height: 20 };
    }
}

// initial load
loadLevel(1);

// ------------------
// INPUT
// ------------------
let keys = {};
window.addEventListener('keydown', e => keys[e.code] = true);
window.addEventListener('keyup', e => keys[e.code] = false);

// ------------------
// LOOP (MINIMAL CHANGE)
// ------------------
function gameLoop(currentTime) {
    let deltaTime = (currentTime - lastTime) / 1000;
    if (deltaTime > 0.1) deltaTime = 0.1;
    lastTime = currentTime;

    timeSinceLastCandy += deltaTime;
    if (timeSinceLastCandy >= 1.0) {
        candies++;
        timeSinceLastCandy = 0;
        updateUI();
    }

    if (gameState === 'PLAYING') {
        updatePhysics(deltaTime);
    }

    updateCamera();   // added
    drawCanvas();     // always draw

    requestAnimationFrame(gameLoop);
}

// ------------------
// CAMERA FOLLOW (NEW)
// ------------------
function updateCamera() {
    let targetY = player.y - canvas.height / 2 + 120;
    cameraY += (targetY - cameraY) * 0.1;
}

// ------------------
// PHYSICS (UNCHANGED)
// ------------------
function updatePhysics(deltaTime) {

    if (keys['ArrowRight']) player.vx = player.speed;
    else if (keys['ArrowLeft']) player.vx = -player.speed;
    else player.vx = 0;

    player.x += player.vx * deltaTime;

    player.vy += gravity * deltaTime;
    player.y += player.vy * deltaTime;

    player.grounded = false;

    for (let obs of obstacles) {
        if (obs.moving) {
            obs.x += obs.direction * obs.speed * deltaTime;

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

            if (obs.moving) {
                player.x += obs.direction * obs.speed * deltaTime;
            }
        }
    }

    if (keys['Space'] && player.grounded) {
        player.vy = -player.jumpHeight;
    }

    if (
        player.x < star.x + star.width &&
        player.x + player.width > star.x &&
        player.y < star.y + star.height &&
        player.y + player.height > star.y
    ) {
        nextLevel = player.level + 1;
        gameState = 'MENU';
        document.getElementById('levelStatus').style.display = 'block';
        updateUI();
    }

    if (player.y > canvas.height + 100) {
        player.x = 50;
        player.y = 350;
        player.vy = 0;
    }
}

// ------------------
// DRAW (MODIFIED FOR CAMERA)
// ------------------
function drawCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.save();
    ctx.translate(0, -cameraY);

    ctx.fillStyle = '#888';
    for (let obs of obstacles) {
        ctx.fillRect(obs.x, obs.y, obs.width, obs.height);
    }

    ctx.fillStyle = 'yellow';
    ctx.beginPath();
    ctx.moveTo(star.x + star.width / 2, star.y);
    ctx.lineTo(star.x + star.width, star.y + star.height / 2);
    ctx.lineTo(star.x + star.width / 2, star.y + star.height);
    ctx.lineTo(star.x, star.y + star.height / 2);
    ctx.closePath();
    ctx.fill();

    ctx.fillStyle = player.grounded ? '#39ff14' : '#00ffff';
    ctx.fillRect(player.x, player.y, player.width, player.height);

    ctx.restore();
}

// ------------------
// UI (UNCHANGED)
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
// UPGRADES (UNCHANGED)
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

        player.level = nextLevel;
        loadLevel(player.level);

        document.getElementById('levelStatus').style.display = 'none';
        storyText.innerText = "";

        gameState = 'PLAYING';
        updateUI();
        saveGame();
    }
}

// ------------------
// SAVE (UNCHANGED)
// ------------------
function saveGame() {
    fetch('save.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            playerId: "student_dev_1",
            health: player.health,
            candies: candies,
            level: player.level,
            stats: { jumpHeight: player.jumpHeight, speed: player.speed }
        })
    });
}

<<<<<<< HEAD
requestAnimationFrame(gameLoop);
=======
requestAnimationFrame(gameLoop);
>>>>>>> a3d0d9e5aa49ad4bb8f98b513c6d11e5eb4025b6

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
    invincible: false,
    grounded: false
};

let gravity = 800;

// ------------------
// LEVEL DATA
// ------------------
let obstacles = [];
let enemies = [];

let enem_knight_r = document.getElementById("e_knight_r");
let enem_knight_l = document.getElementById("e_knight_l");

let hitbox = {
    width: 20,
    height: 20
};

let borderL = { 
    x: 0, 
    y: 0, 
    width: -50, 
    height: canvas.height 
};

let borderR = { 
    x: canvas.width, 
    y: 0, 
    width: 50, 
    height: canvas.height 
};

let star = { x: 0, y: 0, width: 20, height: 20 };

// ------------------
// LOAD LEVELS
// ------------------
function loadLevel(level) {

    player.x = 50;
    player.y = 450;
    player.vx = 0;
    player.vy = 0;

    cameraY = 0; // reset camera

    if (level === 1) {

        obstacles = [
            { x: 0, y: 480, width: 800, height: 20 },
            { x: 100, y: 420, width: 120, height: 20 },
            { x: 300, y: 360, width: 120, height: 20 },
            { x: 150, y: 300, width: 120, height: 20, moving: true, minX: 100, maxX: 500, direction: 1, speed: 120 },
            { x: 400, y: 240, width: 120, height: 20 },
            { x: 250, y: 180,  width: 120, height: 20 }
        ];

        star = { x: 280, y: 140, width: 20, height: 20 };
    }

    if (level === 2) {
        obstacles = [
            { x: 0, y: 480, width: 120, height: 20 },
            { x: 150, y: 430, width: 120, height: 20 },
            { x: 330, y: 380, width: 120, height: 20 },
            { x: 180, y: 310, width: 120, height: 20 },
            { x: 280, y: 220, width: 120, height: 20 },

            { x: 160, y: 190, width: 120, height: 20, moving: true, minX: 100, maxX: 500, direction: 1, speed: 90 },
            { x: 320, y: 160, width: 120, height: 20, moving: true, minX: 150, maxX: 550, direction: -1, speed: 100 }
        ];

        enemies = [{
            x: 330, 
            y: 355, 
            width: 20, 
            height: 25, 
            minX: 330,
            maxX: 430, 
            moving: true,
            direction: 1,
            speed: 50,
            right_sprite: enem_knight_r, 
            left_sprite: enem_knight_l }
        ]

        star = { x: 260, y: 120, width: 20, height: 20 };
    }
}

// initial load
loadLevel(1);

// ------------------
// INPUT
// ------------------
let keys = {};
document.addEventListener('keydown', e => {
    keys[e.code] = true;
    if (e.code === 'Space') {
        e.preventDefault();
    }
});
document.addEventListener('keyup', e => keys[e.code] = false);

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
    let targetY = player.y - (canvas.height / 2 + 85);
    cameraY += (targetY - cameraY) * 0.1;
}

// ------------------
// PHYSICS (UNCHANGED)
// ------------------
function updatePhysics(deltaTime) {
    if (keys['ArrowRight'] || keys['KeyD']) player.vx = player.speed;
    else if (keys['ArrowLeft'] || keys['KeyA']) player.vx = -player.speed;
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

    for (let e of enemies) {
        if (player.x <= e.x + e.width &&
            player.x + player.width >= e.x &&
            player.y <= e.y + e.height &&
            player.y + player.height >= e.y &&
            player.invincible == false
        ) {
            let sfxHurt = document.getElementById("sfx_hurt");
            sfxHurt.play();

            player.invincible = true;

            player.health -= 20;
            updateUI();

            setTimeout(function() {
                player.invincible = false;
            }, 2500)
        }
    }

    for (let e of enemies) {
        if (e.moving) {
            e.x += e.direction * e.speed * deltaTime;

            if (e.x <= e.minX) {
                e.x = e.minX;
                e.direction = 1;
            } 
            else if (e.x >= e.maxX) {
                e.x = e.maxX;
                e.direction = -1;
            }
        }
    }

    if (player.x <= borderL.x) {
        player.x = 0;
    }

    if (player.x >= borderR.x - 20) {
        player.x = canvas.width - 20;
    }

    if ( (keys['ArrowUp'] || keys['KeyW'] || keys['Space']) && player.grounded) {
        let sfxJump = document.getElementById("sfx_jump");
        sfxJump.play();

        player.vy = -player.jumpHeight;
    }

    if (
        player.x < star.x + star.width &&
        player.x + player.width > star.x &&
        player.y < star.y + star.height &&
        player.y + player.height > star.y
    ) {
        let sfxWin = document.getElementById("sfx_win");
        sfxWin.play();

        nextLevel = player.level + 1;
        gameState = 'MENU';
        document.getElementById('levelStatus').style.display = 'block';
        updateUI();
    }

    if (player.y > canvas.height + 100) {
        player.x = 50;
        player.y = 450;
        player.vy = 0;

        let sfxHurt = document.getElementById("sfx_hurt");
        sfxHurt.play();

        player.health -= 20;
        player.invincible = false;
        updateUI();
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

    for (let e of enemies) {
        let sprite = e.direction === 1 ? e.right_sprite : e.left_sprite;
        ctx.drawImage(sprite, e.x, e.y, e.width, e.height);
    }

    ctx.fillRect(borderL.x, borderL.y, borderL.width, borderL.height);
    ctx.fillRect(borderR.x, borderR.y, borderR.width, borderR.height);

    ctx.fillStyle = 'yellow';
    ctx.beginPath();
    ctx.moveTo(star.x + star.width / 2, star.y);
    ctx.lineTo(star.x + star.width, star.y + star.height / 2);
    ctx.lineTo(star.x + star.width / 2, star.y + star.height);
    ctx.lineTo(star.x, star.y + star.height / 2);
    ctx.closePath();
    ctx.fill();

    if (player.invincible == false) {
        ctx.fillStyle = player.grounded ? '#39ff14' : '#00ffff';
        ctx.fillRect(player.x, player.y, player.width, player.height);
    } else {
        ctx.globalAlpha = 0.5;
        ctx.fillStyle = player.grounded ? '#39ff14' : '#00ffff';
        ctx.fillRect(player.x, player.y, player.width, player.height);
        ctx.globalAlpha = 1;
    }
    ctx.restore();
}

// ------------------
// UI (UNCHANGED)
// ------------------
const resourceDisplay = document.getElementById('resourceDisplay');
const healthDisplay = document.getElementById('healthDisplay');
const upgradeButtons = document.getElementById('upgrade-buttons');
const nextlevelButton = document.getElementById('next-level-button');
const storyText = document.getElementById('storyText');

function updateUI() {
    nextlevelButton.addEventListener('click', () => applyUpgrade('none'));

    resourceDisplay.innerText = `Candies: ${candies}`;
    healthDisplay.innerText = `Health: ${player.health}`;
    if (gameState === 'MENU') {
        nextlevelButton.style.display = 'block';

        if (candies >= 10) {
            upgradeButtons.style.display = 'block';

            if (!storyText.innerText.includes("merchant")) {
                storyText.innerText = "The candy merchant appears and offers you upgrades...";

                let sfxMerchant1 = document.getElementById("sfx_hey");
                sfxMerchant1.play();
            }
        }
    }
}

// ------------------
// UPGRADES (UNCHANGED)
// ------------------
document.getElementById('btnUpgradeHealth').addEventListener('click', () => applyUpgrade('health'));
document.getElementById('btnUpgradeJump').addEventListener('click', () => applyUpgrade('jump'));
document.getElementById('btnUpgradeSpeed').addEventListener('click', () => applyUpgrade('speed'));

function applyUpgrade(type) {
    if (type === 'none') {
        nextlevelButton.style.display = 'none'; 
        upgradeButtons.style.display = 'none';
        
        player.level = nextLevel;
        loadLevel(player.level);

        document.getElementById('levelStatus').style.display = 'none';
        storyText.innerText = "";

        gameState = 'PLAYING';
        updateUI();
        saveGame();
    } else if (candies >= 10) {
        candies -= 10;

        let sfxUpgrade = document.getElementById("sfx_upgrade");
        sfxUpgrade.play();

        let sfxMerchant2 = document.getElementById("sfx_comeagain");
        sfxMerchant2.play();

        if (type === 'health') player.health += 20;
        if (type === 'jump') player.jumpHeight += 50;
        if (type === 'speed') player.speed += 50;

        nextlevelButton.style.display = 'none'; 
        upgradeButtons.style.display = 'none'; 

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

requestAnimationFrame(gameLoop);
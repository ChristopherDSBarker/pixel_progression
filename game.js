// ------------------
// CANVAS SETUP
// ------------------
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

let gameState = 'PLAYING';
let lastTime = performance.now();
let interval = 0;

let candies = 0;
let timeSinceLastCandy = 0;

let musGame = document.getElementById("mus_game");
musGame.loop = true;

// ------------------
// MUSIC SETUP
// ------------------

if (gameState === 'PLAYING') {
    musGame.play();
}

const sfxJump = document.getElementById("sfx_jump");
const sfxHurt = document.getElementById("sfx_hurt");
const sfxFireball = document.getElementById("sfx_fireball");
const sfxBullet = document.getElementById("sfx_bullet");

const sfxWin = document.getElementById("sfx_win");
const sfxOver = document.getElementById("sfx_over");
const sfxHover = document.getElementById("sfx_hover");

const sfxHealth = document.getElementById("sfx_heart");
const sfxHealthF = document.getElementById("sfx_heartf");
const sfxShield = document.getElementById("sfx_shield");

const sfxMerchant1 = document.getElementById("sfx_hey");
const sfxUpgrade = document.getElementById("sfx_upgrade");
const sfxMerchant2 = document.getElementById("sfx_comeagain");
const sfxBroke = document.getElementById("sfx_broke");

const musGameOver = document.getElementById("mus_gameover");

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
    health: 100,
    maxHealth: 100,
    level: 1,
    shielded: false,
    invincible: false,
    grounded: false
};

let gravity = 800;

// ------------------
// LEVEL DATA
// ------------------
let obstacles = [];

/* 'h' -> +25 health, 'f' -> +full health, 's' -> bubble shield, 
'd' -> health drink, 'c' -> candy */
let powerups = [];
let enemies = [];
let projectiles = [];

const p_heart = document.getElementById("p_heart");
const p_shield = document.getElementById("p_shield");

const enem_knight_r = document.getElementById("e_knight_r");
const enem_knight_l = document.getElementById("e_knight_l");

const enem_ninja_r = document.getElementById("e_ninja_r");
const enem_ninja_l = document.getElementById("e_ninja_l");

const enem_wizard_r = document.getElementById("e_wizard_r");
const enem_wizard_l = document.getElementById("e_wizard_l");
const enem_fireball = document.getElementById("e_fireball");

const enem_marksman_r = document.getElementById("e_marksman_r");
const enem_marksman_l = document.getElementById("e_marksman_l");
const enem_bullet_r = document.getElementById("e_bullet_r");
const enem_bullet_l = document.getElementById("e_bullet_l");

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
    obstacles = [];
    enemies = [];
    powerups = [];
    projectiles = [];

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
            { x: 150, y: 300, width: 120, height: 20, moving: 'x', minX: 100, maxX: 500, direction: 1, speed: 120 },
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
            { x: 170, y: 360, width: 80, height: 20 },
            { x: 170, y: 310, width: 80, height: 20, moving: 'x', minX: 100, maxX: 500, direction: 1, speed: 90 },
            { x: 330, y: 260, width: 50, height: 20 },
            { x: 20, y: 210, width: 70, height: 20, moving: 'x', minX: 20, maxX: 280, direction: 1, speed: 90 },
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
            damage: 20,
            right_sprite: enem_knight_r, 
            left_sprite: enem_knight_l }
        ]

        star = { x: 260, y: 120, width: 20, height: 20 };
    }

    if (level === 3) {
        obstacles = [
            { x: 0, y: 480, width: 120, height: 20 },
            { x: 240, y: 480, width: 50, height: 20 },
            { x: 340, y: 380, width: 120, height: 20, moving: 'y', minY: 250, maxY: 450, direction: 1, speed: 90},
            { x: 340, y: 190, width: 60, height: 20 },
            { x: 100, y: 340, width: 150, height: 20 },
            { x: 10, y: 300, width: 50, height: 20 },
            { x: 10, y: 250, width: 50, height: 20 },
            { x: 10, y: 200, width: 100, height: 20 },

        ];

        enemies = [{
            x: 200, 
            y: 320, 
            width: 20, 
            height: 20, 
            minX: 100,
            maxX: 230, 
            moving: true,
            direction: 1,
            speed: 150,
            damage: 10,
            right_sprite: enem_ninja_r, 
            left_sprite: enem_ninja_l },

            {
            x: 10, 
            y: 175, 
            width: 20, 
            height: 25, 
            minX: 10,
            maxX: 90, 
            moving: true,
            direction: 1,
            speed: 50,
            damage: 20,
            right_sprite: enem_knight_r, 
            left_sprite: enem_knight_l }
        ];

        powerups = [{
            x: 340, 
            y: 130, 
            width: 30, 
            height: 35,  
            type: 'h',
            sprite: p_heart }
        ];

        star = { x: 200, y: 130, width: 20, height: 20 };
    }

    if (level === 4) {
        obstacles = [
            { x: 0, y: 480, width: 300, height: 20 },
            { x: 400, y: 480, width: 30, height: 20 },

        ];

        enemies = [{
            x: 400, 
            y: 455, 
            width: 20, 
            height: 25, 
            minX: 0,
            maxX: 0, 
            moving: false,
            direction: -1,
            speed: 0,
            damage: 30,
            right_sprite: enem_marksman_r, 
            left_sprite: enem_marksman_l },
        ];

        projectiles = [{
            x: 400, 
            y: 455, 
            width: 20, 
            height: 4, 
            minX: canvas.width,
            maxX: 400,
            direction: -1,
            speed: 20,
            damage: 25,
            type: 'b',
            hit: false,
            right_sprite: enem_bullet_r,
            left_sprite: enem_bullet_l }
        ];

        powerups = [{
            x: 150, 
            y: 445, 
            width: 30, 
            height: 35,  
            type: 's',
            sprite: p_shield }
        ];

        star = { x: 200, y: 130, width: 20, height: 20 };
    }
}

// initial load
loadLevel(1);

// ------------------
// INPUT
// ------------------
let keys = {};
document.addEventListener('keydown', e => {
    let keyBlock = ["ArrowLeft", "ArrowRight", " "];
    keys[e.code] = true;
    if (keyBlock.includes(e.key)) {
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

    if (gameState === 'PLAYING') {
        timeSinceLastCandy += deltaTime;
        if (timeSinceLastCandy >= 1.0) {
            candies++;
            timeSinceLastCandy = 0;
            updateUI();
        }

        if (player.health <= 0) {
            player.health = 0;
            gameState = 'GAMEOVER';
            updateUI();
        }
        updatePhysics(deltaTime);
    }

    updateCamera();   // added
    if (gameState === 'PLAYING' || gameState === 'MENU') { drawCanvas(); }     // draw when game is in motion
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

    // TESTING
    if (keys['KeyI'] && keys['KeyP']) player.health = 0;
    if (keys['KeyF'] && keys['KeyP']) player.health = 999999;
    if (keys['KeyJ'] && keys['KeyP']) player.jumpHeight = 500;
    if (keys['KeyC'] && keys['KeyP']) {
        player.x = star.x;
        player.y = star.y;
    }

    player.x += player.vx * deltaTime;

    player.vy += gravity * deltaTime;
    player.y += player.vy * deltaTime;

    player.grounded = false;

    for (let obs of obstacles) {
        if (obs.moving === 'x') {
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

        if (obs.moving === 'y') {
            obs.y += obs.direction * obs.speed * deltaTime;

            if (obs.y <= obs.minY) {
                obs.y = obs.minY;
                obs.direction = 1;
            } 
            else if (obs.y + obs.height >= obs.maxY) {
                obs.y = obs.maxY - obs.height;
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

            if (obs.moving === 'x') {
                player.x += obs.direction * obs.speed * deltaTime;
            }

            if (obs.moving === 'y') {
                player.y += obs.direction * obs.speed * deltaTime;
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
            if (player.shielded) {
                player.shielded = false;
                sfxShield.play();
            } else {
                player.health -= e.damage;
                sfxHurt.play();
            }

            player.invincible = true;

            setTimeout(function() {
                player.invincible = false;
                }, 2000)
            updateUI();
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

    for (let pr of projectiles) {
        if (player.x <= pr.x + pr.width &&
            player.x + player.width >= pr.x &&
            player.y <= pr.y + pr.height &&
            player.y + player.height >= pr.y &&
            player.invincible == false
        ) { 
            if (player.shielded) {
                player.shielded = false;
                sfxShield.play();
            } else {
                player.health -= pr.damage;
                sfxHurt.play();
            }
            player.invincible = true;

            pr.x = -1000;
            pr.y = -1000;

            setTimeout(function() {
                player.invincible = false;
                }, 2000)
            updateUI();
        }
    }

    
    for (let pr of projectiles) {
        if (pr.type === 'f') {
            console.log("Imagine a fireball projectile here")

        }

        if (pr.type === 'b') {
            interval += deltaTime;
            if (interval >= 3) {
                interval = 0;
                ctx.drawImage(p.sprite, p.x, p.y, p.width, p.height);
            }
                pr.x += pr.direction * pr.speed * deltaTime;
        }
    }

    for (let p of powerups) {
        if (player.x <= p.x + p.width &&
            player.x + player.width >= p.x &&
            player.y <= p.y + p.height &&
            player.y + player.height >= p.y
        ) {
            if (p.type === 'h') {
                sfxHealth.play();

                if (player.health <= (player.maxHealth - 25)) {
                    player.health += 25;
                }
                else {
                    player.health = player.maxHealth;
                }

                ctx.globalAlpha = 0;
                ctx.drawImage(p.sprite, p.x, p.y, p.width, p.height);
                ctx.globalAlpha = 1;
                p.x = -1000;
                p.y = -1000;

                updateUI();
            }

            else if (p.type === 'f') {
                sfxHealthF.play();
                player.health = player.maxHealth;

                ctx.globalAlpha = 0;
                ctx.drawImage(p.sprite, p.x, p.y, p.width, p.height);
                ctx.globalAlpha = 1;
                p.x = -1000;
                p.y = -1000;

                updateUI();
            }
        
            else if (p.type === 's') {
                if (player.shielded === false) sfxShield.play();

                player.shielded = true;

                p.x = -1000;
                p.y = 1000;
                
                updateUI();
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
        sfxJump.play();

        player.vy = -player.jumpHeight;
    }

    if (
        player.x < star.x + star.width &&
        player.x + player.width > star.x &&
        player.y < star.y + star.height &&
        player.y + player.height > star.y
    ) {
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

        sfxHurt.play();

        player.health -= 20;
        player.invincible = false;
        if (player.shielded) player.shielded = false;
    
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

    for (let p of powerups) {
        ctx.drawImage(p.sprite, p.x, p.y, p.width, p.height);
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

    if (player.shielded) ctx.drawImage(p_shield, player.x - 5, player.y - 10, 30, 35);

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
    resourceDisplay.innerText = `Candies: ${candies}`;
    healthDisplay.innerText = `Health: ${player.health}`;
    nextlevelButton.addEventListener('click', () => applyUpgrade('none'));

    if (gameState === 'MENU') {
        nextlevelButton.style.display = 'block';

        if (!storyText.innerText.includes("merchant")) {
            storyText.innerText = "The candy merchant appears and offers you upgrades...";
            sfxMerchant1.play();

            upgradeButtons.style.display = 'block';
        }
    }

    if (gameState === 'GAMEOVER') {
        canvas.style.display = 'none';
        resourceDisplay.style.display = 'none';
        healthDisplay.style.display = 'none';
        let logoutBtn = document.getElementById("logout");
        logoutBtn.style.display = 'none';

        gameOver();
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
    } else {
        if (type === 'health' && candies >= 15) {
            candies -= 15;

            player.maxHealth += 20;
            player.health += 20;
        }
        else if (type === 'health' && candies < 15) {
            sfxBroke.currentTime = 0;
            sfxBroke.play();
            storyText.innerText = "Not enough candies!";
            applyUpgrade(type);
        }

        if (type === 'jump' && candies >= 20) {
            candies -= 20;

            player.jumpHeight += 50;
        }
        else if (type === 'jump' && candies < 20) {
            sfxBroke.currentTime = 0;
            sfxBroke.play();
            storyText.innerText = "Not enough candies!";
            applyUpgrade(type);
        }

        if (type === 'speed' && candies >= 10) {
            candies -= 10;
            
            player.speed += 50;
        }
        else if (type === 'speed' && candies < 10) {
            sfxBroke.currentTime = 0;
            sfxBroke.play();
            storyText.innerText = "Not enough candies!";
            applyUpgrade(type);
        }

        sfxUpgrade.play();
        sfxMerchant2.play();

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
// GAME OVER
// ------------------
function gameOver() {
    document.querySelectorAll("audio").forEach(audio => {
        audio.pause();
        audio.currentTime = 0;
    });

    musGame.loop = false;
    musGame.pause();
    musGame.currentTime = 0;

    sfxOver.play();

    setTimeout(function() {
        musGameOver.loop = true;
        musGameOver.play();

        let overText = document.getElementById("overText");
        overText.style.display = 'block';
        let btnRetry = document.getElementById("retry");
        btnRetry.style.display = 'flex';
        let btnHome = document.getElementById("home");
        btnHome.style.display = 'flex';

        let buttons = document.querySelectorAll(".menu-button");

        buttons.forEach(b => {
            b.addEventListener("mouseover", function() {
                b.style.backgroundColor = "#222";
                sfxHover.currentTime = 0;
                sfxHover.play();
         });
            b.addEventListener("mouseout", function() {
                b.style.backgroundColor = "black";
            });
        });
    }, 2500)
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

if (gameState !== 'GAMEOVER') {
    requestAnimationFrame(gameLoop);
} 
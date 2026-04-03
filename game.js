const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// ------------------
// GAME STATE
// ------------------
let state = "menu"; // menu → gameplay → post

// ------------------
// PLAYER
// ------------------
let player = {
    x: 50,
    y: 300,
    w: 30,
    h: 30,
    dy: 0,
    gravity: 0.8,
    jump: -12,
    grounded: true,
    health: 100,
    invincible: false
};

let speedBonus = 0;
let jumpBonus = 0;

// ------------------
// OBSTACLE
// ------------------
let obstacle = {
    x: 600,
    y: 300,
    w: 30,
    h: 30,
    speed: 5
};

// ------------------
// INPUT
// ------------------
document.addEventListener("keydown", (e) => {
    if (state === "gameplay" && e.code === "Space" && player.grounded) {
        player.dy = player.jump + jumpBonus;
        player.grounded = false;
    }
});

// ------------------
// GAME UPDATE
// ------------------
function update() {
    // gravity
    player.dy += player.gravity;
    player.y += player.dy;

    // ground
    if (player.y >= 300) {
        player.y = 300;
        player.dy = 0;
        player.grounded = true;
    }

    // move obstacle
    obstacle.x -= (obstacle.speed + speedBonus);

    if (obstacle.x < -30) {
        obstacle.x = 800;

        // level complete
        state = "post";
        showPost();
    }

    // collision
    if (
        player.x < obstacle.x + obstacle.w &&
        player.x + player.w > obstacle.x &&
        player.y < obstacle.y + obstacle.h &&
        player.y + player.h > obstacle.y
    ) {
        if (!player.invincible) {
            player.health -= 10;
            player.invincible = true;

            setTimeout(() => {
                player.invincible = false;
            }, 1000);
        }
    }

    document.getElementById("health").innerText = player.health;
}

// ------------------
// DRAW
// ------------------
function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.fillStyle = player.invincible ? "yellow" : "lime";
    ctx.fillRect(player.x, player.y, player.w, player.h);

    ctx.fillStyle = "red";
    ctx.fillRect(obstacle.x, obstacle.y, obstacle.w, obstacle.h);
}

// ------------------
// LOOP
// ------------------
function loop() {
    if (state === "gameplay") {
        update();
        draw();
    }
    requestAnimationFrame(loop);
}
loop();

// ------------------
// UI LOGIC
// ------------------
function chooseUpgrade(type) {
    if (type === "jump") jumpBonus -= 3;
    if (type === "speed") speedBonus += 1;

    document.getElementById("menu").classList.add("hidden");
    state = "gameplay";
}

function showPost() {
    document.getElementById("post").classList.remove("hidden");
}

function postChoice(choice) {
    if (choice === "heal") player.health += 20;

    document.getElementById("post").classList.add("hidden");
    document.getElementById("menu").classList.remove("hidden");

    state = "menu";
}

// ------------------
// SAVE TO BACKEND
// ------------------
function saveGame() {
    fetch("save.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            health: player.health,
            speed: speedBonus,
            jump: jumpBonus
        })
    });
}

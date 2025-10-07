<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carregando...</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; background: #000; overflow: hidden; cursor: none; }
        canvas { display: block; }
        .loading-text {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: #00ff80;
            font-family: 'VT323', monospace, sans-serif;
            font-size: 1.5rem;
            text-shadow: 0 0 5px #00ff80;
            opacity: 1;
            transition: opacity 0.5s;
            pointer-events: none;
        }
    </style>
</head>
<body>

<canvas id="fightCanvas"></canvas>
<div id="loadingText" class="loading-text">INICIALIZANDO SISTEMA...</div>

<script>
const canvas = document.getElementById('fightCanvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// --- Efeitos e Partículas ---
const particles = [];

function createExplosion(x, y, color) {
    for (let i = 0; i < 50; i++) {
        particles.push({
            x: x, y: y,
            vx: (Math.random() - 0.5) * 8,
            vy: (Math.random() - 0.5) * 8,
            life: 50,
            color: color
        });
    }
}

function updateParticles() {
    for (let i = particles.length - 1; i >= 0; i--) {
        const p = particles[i];
        p.x += p.vx;
        p.y += p.vy;
        p.life--;
        if (p.life <= 0) particles.splice(i, 1);

        ctx.fillStyle = p.color;
        ctx.globalAlpha = p.life / 50;
        ctx.beginPath();
        ctx.arc(p.x, p.y, 2, 0, Math.PI * 2);
        ctx.fill();
        ctx.globalAlpha = 1;
    }
}

// --- Robôs ---
class Robot {
    constructor(x, color, name) {
        this.x = x;
        this.y = canvas.height / 2;
        this.color = color;
        this.name = name;
        this.hp = 100;
        this.isHit = 0;
        this.isDefeated = false;
    }

    draw() {
        if (this.isDefeated) return;

        let currentColor = this.isHit > 0 ? '#ffffff' : this.color;
        if (this.isHit > 0) this.isHit--;
        
        ctx.shadowColor = currentColor;
        ctx.shadowBlur = 20;

        ctx.fillStyle = currentColor;
        ctx.fillRect(this.x - 25, this.y - 40, 50, 80); // Corpo
        ctx.fillRect(this.x - 15, this.y - 60, 30, 20); // Cabeça

        ctx.fillStyle = '#000';
        ctx.fillRect(this.x + 5, this.y - 55, 10, 10); // Olho
        
        ctx.shadowBlur = 0;
        
        ctx.fillStyle = '#333';
        ctx.fillRect(this.x - 50, this.y - 80, 100, 10);
        ctx.fillStyle = this.color;
        ctx.fillRect(this.x - 50, this.y - 80, this.hp, 10); // Barra de HP
    }
    
    takeDamage(amount) {
        this.hp -= amount;
        this.isHit = 10;
        if (this.hp <= 0) {
            this.hp = 0;
            this.isDefeated = true;
            createExplosion(this.x, this.y, this.color);
        }
    }
}

const hero = new Robot(canvas.width * 0.25, '#00ff80', 'HERO');
const villain = new Robot(canvas.width * 0.75, '#ff0000', 'VILLAIN');

// --- Lógica da Luta (Script) ---
let fightState = 'START';
let fightTimer = 0;

function animateFight() {
    fightTimer++;

    if (fightState === 'START' && hero.x < canvas.width / 2 - 100) {
        hero.x += 2;
        villain.x -= 2;
    } else if (fightState === 'START') {
        fightState = 'FIGHTING';
        fightTimer = 0;
    }
    
    if (fightState === 'FIGHTING') {
        if (fightTimer % 60 === 0) { // A cada 60 frames (aprox. 1s)
            if (Math.random() > 0.3) { // Herói verde ataca com mais frequência
                createExplosion(villain.x, villain.y, hero.color);
                villain.takeDamage(25 + Math.random() * 10);
            } else {
                createExplosion(hero.x, hero.y, villain.color);
                hero.takeDamage(10 + Math.random() * 5); // Vilão vermelho dá menos dano
            }
        }
    }
    
    if (villain.isDefeated && fightState !== 'END') {
        fightState = 'END';
        fightTimer = 0;
        document.getElementById('loadingText').innerText = 'ACESSO AUTORIZADO';
    }
    
    if (fightState === 'END' && fightTimer > 120) { // Espera 2 segundos após a vitória
        document.getElementById('loadingText').style.opacity = '0';
        redirectToDashboard();
        return; // Para o loop de animação
    }

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hero.draw();
    villain.draw();
    updateParticles();
    
    requestAnimationFrame(animateFight);
}

let redirected = false;
function redirectToDashboard() {
    if (!redirected) {
        redirected = true;
        // Redireciona para a dashboard
        window.location.href = '{{ route("dashboard") }}';
    }
}

// Inicia a animação
animateFight();

</script>
</body>
</html>
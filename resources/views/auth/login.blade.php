<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Finance AI Login</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
body { margin:0; background:#0b0b0b; overflow:hidden; }
.login-card {
    opacity: 0;
    transform: translateY(-100px) scale(0.95);
    transition: all 1s ease-out;
}
.login-card.show {
    opacity: 1;
    transform: translateY(0) scale(1);
}
.neon-text {
    position: absolute;
    top: 5%;
    left: 50%;
    transform: translateX(-50%);
    font-size: 4rem;
    font-weight: bold;
    color: #00ff80;
    text-shadow: 0 0 5px #00ff80, 0 0 10px #00ff80, 0 0 20px #00ff80, 0 0 40px #00ff80;
    font-family: 'Arial', sans-serif;
}
</style>
</head>
<body>

<canvas id="chartCanvas" class="fixed inset-0 w-full h-full z-0"></canvas>

<div class="neon-text">Finança & AI 🤖</div>

<div id="loginCard" class="absolute top-20 right-12 z-10 p-8 w-[400px] bg-black bg-opacity-80 rounded-xl shadow-xl login-card">
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" class="text-white" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="text-white" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mb-4 flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-gray-300">{{ __('Remember me') }}</label>
        </div>
        <div class="flex justify-end items-center mt-4">
            @if (Route::has('password.request'))
            <a class="text-sm text-gray-400 hover:text-white underline" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif
            <x-primary-button class="ml-3">{{ __('Log in') }}</x-primary-button>
        </div>
    </form>
</div>

<script>
const canvas = document.getElementById('chartCanvas');
const ctx = canvas.getContext('2d');

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

// --- Gráfico de barras ---
const bars = [];
const BAR_COUNT = 50;

class Bar {
    constructor(x){
        this.x = x;
        this.height = Math.random() * 200 + 50;
        this.targetHeight = this.height;
        this.width = 8;
        this.speed = 0.02;
    }
    update(){
        this.targetHeight = 50 + Math.random()*200;
        this.height += (this.targetHeight - this.height)*this.speed;
    }
    draw(){
        ctx.fillStyle = 'rgba(0,255,128,0.7)';
        ctx.fillRect(this.x, canvas.height - this.height, this.width, this.height);
    }
}

function initBars(){
    const spacing = canvas.width / BAR_COUNT;
    for(let i=0;i<BAR_COUNT;i++){
        bars.push(new Bar(i*spacing + spacing/2));
    }
}

function drawTrendLine(){
    ctx.beginPath();
    ctx.strokeStyle = 'rgba(0,255,128,0.5)';
    ctx.lineWidth = 2;
    for(let i=0;i<bars.length;i++){
        const bx = bars[i].x + bars[i].width/2;
        const by = canvas.height - bars[i].height;
        if(i===0) ctx.moveTo(bx, by);
        else ctx.lineTo(bx, by);
    }
    ctx.stroke();
}

// --- Robô futurista ---
const robot = { x: canvas.width/2, y: 150, radius: 40, angle: 0 };

function drawRobot() {
    // Corpo
    ctx.save();
    ctx.translate(robot.x, robot.y);
    ctx.rotate(Math.sin(robot.angle)*0.2);
    ctx.fillStyle = '#00ff80';
    ctx.strokeStyle = '#00ff80';
    ctx.lineWidth = 3;
    ctx.beginPath();
    ctx.arc(0,0,robot.radius,0,Math.PI*2);
    ctx.fill();
    ctx.stroke();
    // Olhos
    ctx.fillStyle = '#0b0b0b';
    ctx.beginPath();
    ctx.arc(-15,-10,5,0,Math.PI*2);
    ctx.arc(15,-10,5,0,Math.PI*2);
    ctx.fill();
    ctx.restore();
}

function animate(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    bars.forEach(b => { b.update(); b.draw(); });
    drawTrendLine();
    drawRobot();
    robot.angle += 0.02;
    requestAnimationFrame(animate);
}

initBars();
animate();

window.addEventListener('load', ()=>{
    document.getElementById('loginCard').classList.add('show');
});
</script>

</body>
</html>

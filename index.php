<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Skill Exchange 🌍</title>

<style>
/* ===== RESET ===== */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', Arial, sans-serif;
}
html{scroll-behavior:smooth;}

/* ===== BODY BACKGROUND ===== */
body{
    min-height:100vh;
    background:
        radial-gradient(circle at top left, #4caf50 0%, transparent 40%),
        radial-gradient(circle at bottom right, #0c2c45 0%, transparent 45%),
        linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    color:#fff;
    overflow-x:hidden;
}

/* ===== NAVBAR ===== */
.navbar{
    position:fixed;
    top:0;
    width:100%;
    padding:18px 60px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(14px);
    box-shadow:0 8px 30px rgba(0,0,0,0.3);
    z-index:1000;
}

.logo{
    font-size:26px;
    font-weight:700;
    color:#b9ffca;
}

.nav-links a{
    margin:0 14px;
    text-decoration:none;
    color:#fff;
    font-size:14px;
    font-weight:600;
    position:relative;
}
.nav-links a::after{
    content:"";
    position:absolute;
    width:0;
    height:2px;
    background:#7CFF9A;
    left:0;
    bottom:-6px;
    transition:0.3s;
}
.nav-links a:hover::after{width:100%;}

.nav-buttons a{
    text-decoration:none;
    padding:10px 24px;
    border-radius:30px;
    font-size:14px;
    font-weight:600;
    color:#fff;
    transition:0.3s;
}
.register{
    background:linear-gradient(135deg,#43e97b,#38f9d7);
}
.login{
    background:linear-gradient(135deg,#4facfe,#00f2fe);
    margin-left:10px;
}
.nav-buttons a:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(0,0,0,0.4);
}

/* ===== HERO ===== */
.hero{
    min-height:100vh;
    background:
        linear-gradient(120deg, rgba(0,0,0,0.75), rgba(0,0,0,0.4)),
        url("assets/hero.jpg") center/cover no-repeat;
    display:flex;
    justify-content:center;
    align-items:center;
    padding-top:120px;
}

.hero-content{
    max-width:1000px;
    padding:70px;
    border-radius:30px;
    text-align:center;
    background:rgba(255,255,255,0.18);
    backdrop-filter:blur(18px);
    box-shadow:0 40px 80px rgba(0,0,0,0.45);
    animation:fadeUp 1.3s ease;
}

.hero h1{
    font-size:54px;
    margin-bottom:16px;
}
.hero p{
    font-size:18px;
    opacity:0.95;
    margin-bottom:30px;
}
.hero h2{
    font-weight:500;
    opacity:0.9;
}

.hero-buttons a{
    display:inline-block;
    padding:16px 42px;
    border-radius:40px;
    font-size:15px;
    font-weight:600;
    margin-top:25px;
    color:#fff;
    text-decoration:none;
    transition:0.35s;
}
.hero-buttons a:hover{
    transform:translateY(-6px) scale(1.05);
    box-shadow:0 14px 35px rgba(0,0,0,0.5);
}

/* ===== SECTION COMMON ===== */
.section{
    width:85%;
    margin:140px auto;
    padding:90px 80px;
    border-radius:32px;
    background:rgba(255,255,255,0.16);
    backdrop-filter:blur(18px);
    box-shadow:0 40px 90px rgba(0,0,0,0.45);
    scroll-margin-top:120px;
}

.section h2{
    font-size:46px;
    margin-bottom:40px;
    text-align:center;
}

/* ===== ABOUT ===== */
.about-text{
    font-size:19px;
    line-height:1.9;
    text-align:center;
    opacity:0.95;
}

/* ===== CARDS ===== */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:40px;
    margin-top:50px;
}

.card{
    padding:45px 35px;
    border-radius:28px;
    background:rgba(255,255,255,0.14);
    text-align:center;
    transition:0.4s;
}
.card:hover{
    transform:translateY(-12px) scale(1.03);
    box-shadow:0 20px 50px rgba(0,0,0,0.4);
}
.card span{
    font-size:46px;
    display:block;
    margin-bottom:12px;
}

/* ===== FOOTER ===== */
.footer{
    text-align:center;
    padding:30px;
    opacity:0.85;
    font-size:14px;
}

/* ===== ANIMATION ===== */
@keyframes fadeUp{
    from{opacity:0; transform:translateY(40px);}
    to{opacity:1; transform:translateY(0);}
}

/* ===== RESPONSIVE ===== */
@media(max-width:900px){
    .hero h1{font-size:40px;}
    .section{padding:60px 40px;}
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">Skill Exchange 🤝</div>
    <div class="nav-links">
        <a href="#about">About</a>
        <a href="#work">How It Works</a>
        <a href="#benefits">Benefits</a>
    </div>
    <div class="nav-buttons">
        <a href="auth/register.php" class="register">Register</a>
        <a href="auth/login.php" class="login">Login</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-content">
        <h1>Exchange Skills.<br>No Money Required.</h1>
        <p>
            A platform where <b>skills matter more than money</b>.  
            Learn from others, teach what you know, and grow together 🌱
        </p>
        <h2>Learn • Teach • Grow 🚀</h2>
        <div class="hero-buttons">
            <a href="auth/register.php" class="register">Get Started ✨</a>
        </div>
    </div>
</div>

<!-- ABOUT -->
<section id="about" class="section">
    <h2>About Us 💡</h2>
    <p class="about-text">
        <b>Skill Exchange</b> is a modern skill-sharing platform where learning happens through
        collaboration 🤝.  
        Instead of paying money 💸, users exchange knowledge — one skill for another.  
        From technical skills 💻 to creative talents 🎨, everyone has something valuable to offer.  
        Our mission is simple: <b>make learning accessible, social, and meaningful.</b>
    </p>
</section>

<!-- HOW IT WORKS -->
<section id="work" class="section">
    <h2>How It Works ⚙️</h2>
    <div class="cards">
        <div class="card"><span>📝</span><p>Create your free account</p></div>
        <div class="card"><span>🎯</span><p>Add skills you can teach & want to learn</p></div>
        <div class="card"><span>🔍</span><p>Find people with matching interests</p></div>
        <div class="card"><span>🤝</span><p>Connect, exchange skills & grow</p></div>
    </div>
</section>

<!-- BENEFITS -->
<section id="benefits" class="section">
    <h2>Benefits 🌟</h2>
    <div class="cards">
        <div class="card"><span>💸</span><p>No money involved</p></div>
        <div class="card"><span>🌍</span><p>Community-driven learning</p></div>
        <div class="card"><span>📈</span><p>Boost skills & confidence</p></div>
        <div class="card"><span>⏱️</span><p>Flexible & practical learning</p></div>
    </div>
</section>

<div class="footer">
    © <?php echo date("Y"); ?> Skill Exchange 🌱 | Learn together, grow forever
</div>

</body>
</html>

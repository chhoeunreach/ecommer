@extends('frontend.layouts.app')

@section('content')
<style>
    /* Reset and ensure body scrolls but canvas is fixed */
    body, html {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        background-color: #000;
        color: #fff;
        font-family: 'Inter', sans-serif;
    }
    
    #canvas-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -1;
        pointer-events: none;
    }

    /* Typography & Layout */
    .section {
        position: relative;
        height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10%;
        box-sizing: border-box;
        z-index: 10;
        opacity: 0; /* Animated by GSAP */
    }

    /* Left and Right aligned sections */
    .section-left {
        justify-content: flex-start;
        text-align: left;
    }
    .section-right {
        justify-content: flex-end;
        text-align: right;
    }
    .section-center {
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .content-block {
        max-width: 500px;
        background: rgba(20, 20, 20, 0.4);
        padding: 40px;
        border-radius: 20px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        transform: translateY(30px); /* Animated by GSAP */
    }

    h1 {
        font-size: 4rem;
        font-weight: 800;
        margin-bottom: 20px;
        background: linear-gradient(90deg, #fff, #888);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.1;
    }

    h2 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    p {
        font-size: 1.2rem;
        line-height: 1.6;
        color: #aaa;
        margin-bottom: 30px;
    }

    .btn-premium {
        display: inline-block;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        color: #000;
        background: #fff;
        border-radius: 30px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-premium:hover {
        background: #ddd;
        transform: scale(1.05);
        text-decoration: none;
        color: #000;
    }

    .btn-outline {
        background: transparent;
        color: #fff;
        border: 1px solid #fff;
        margin-left: 15px;
    }
    
    .btn-outline:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    /* Normal scrolling content starts here */
    .static-content {
        position: relative;
        background-color: #0a0a0a;
        z-index: 20;
        padding: 100px 0;
    }

    .shop-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        padding: 40px 10%;
    }

    .product-card {
        background: #111;
        border: 1px solid #222;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        transition: transform 0.3s ease, border-color 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-10px);
        border-color: #444;
    }

    .product-card img {
        width: 150px;
        height: 150px;
        object-fit: contain;
        margin-bottom: 20px;
    }

    .services-bar {
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: wrap;
        background: #111;
        padding: 50px 10%;
        margin-top: 50px;
        border-top: 1px solid #222;
        border-bottom: 1px solid #222;
    }

    .service-item {
        text-align: center;
        color: #888;
        padding: 20px;
    }

    .service-item i {
        font-size: 2rem;
        margin-bottom: 15px;
        color: #fff;
    }
</style>

<!-- 3D Canvas -->
<div id="canvas-container"></div>

<!-- Scroll Sections for 3D animation -->
<div id="scroll-wrapper">
    <!-- Hero / Intro -->
    <div class="section section-left" id="section-hero">
        <div class="content-block">
            <h1>KneaYerng Premium</h1>
            <p>Experience the ultimate device. Unprecedented power, mesmerizing display, and a camera that captures reality.</p>
            <a href="#shop" class="btn-premium">Shop Now</a>
            <a href="#section-camera" class="btn-premium btn-outline">Learn More</a>
        </div>
    </div>

    <!-- Camera -->
    <div class="section section-right" id="section-camera">
        <div class="content-block">
            <h2>Pro Camera System</h2>
            <p>48MP Main. Telephoto. 4K Cinematic video. Capture details you never knew existed.</p>
            <ul style="list-style-type: none; padding: 0;">
                <li style="color:#ddd; margin-bottom: 10px;">🌟 48MP Resolution</li>
                <li style="color:#ddd; margin-bottom: 10px;">🔍 5x Optical Zoom</li>
                <li style="color:#ddd; margin-bottom: 10px;">🎥 4K Cinematic Mode</li>
            </ul>
        </div>
    </div>

    <!-- Performance -->
    <div class="section section-left" id="section-performance">
        <div class="content-block">
            <h2>Unmatched Power</h2>
            <p>Powered by the next-generation bionic chip. Desktop-level gaming, real-time rendering, all with incredible battery life.</p>
        </div>
    </div>

    <!-- Design -->
    <div class="section section-right" id="section-design">
        <div class="content-block">
            <h2>Titanium Build</h2>
            <p>Forged in aerospace-grade titanium. The lightest and strongest model ever created. Available in pure white and natural titanium.</p>
        </div>
    </div>
</div>

<!-- Static Content (Shop & Services) -->
<div class="static-content" id="shop">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2>Shop The Lineup</h2>
        <p>Choose your next device</p>
    </div>

    <div class="shop-grid">
        <!-- Mock Product 1 -->
        <div class="product-card">
            <img src="https://cdn-icons-png.flaticon.com/512/0/191.png" style="filter: invert(1);" alt="Phone">
            <h3 style="color:#fff;">iPhone 15 Pro</h3>
            <p style="color:#888;">From $999</p>
            <a href="#" class="btn-premium" style="padding: 10px 20px; font-size: 0.9rem;">Add to Cart</a>
        </div>
        <!-- Mock Product 2 -->
        <div class="product-card">
            <img src="https://cdn-icons-png.flaticon.com/512/644/644458.png" style="filter: invert(1);" alt="Samsung">
            <h3 style="color:#fff;">Galaxy S24 Ultra</h3>
            <p style="color:#888;">From $1199</p>
            <a href="#" class="btn-premium" style="padding: 10px 20px; font-size: 0.9rem;">Add to Cart</a>
        </div>
        <!-- Mock Product 3 -->
        <div class="product-card">
            <img src="https://cdn-icons-png.flaticon.com/512/3041/3041130.png" style="filter: invert(1);" alt="Mac">
            <h3 style="color:#fff;">MacBook Pro</h3>
            <p style="color:#888;">From $1599</p>
            <a href="#" class="btn-premium" style="padding: 10px 20px; font-size: 0.9rem;">Add to Cart</a>
        </div>
        <!-- Mock Product 4 -->
        <div class="product-card">
            <img src="https://cdn-icons-png.flaticon.com/512/1211/1211756.png" style="filter: invert(1);" alt="Accessories">
            <h3 style="color:#fff;">AirPods Pro</h3>
            <p style="color:#888;">From $249</p>
            <a href="#" class="btn-premium" style="padding: 10px 20px; font-size: 0.9rem;">Add to Cart</a>
        </div>
    </div>

    <div class="services-bar">
        <div class="service-item">
            <h3 style="color: #fff; margin-bottom: 5px;">🔄</h3>
            <h4>Trade-In</h4>
            <p style="font-size: 0.9rem;">Upgrade and save</p>
        </div>
        <div class="service-item">
            <h3 style="color: #fff; margin-bottom: 5px;">💳</h3>
            <h4>Installment</h4>
            <p style="font-size: 0.9rem;">0% interest available</p>
        </div>
        <div class="service-item">
            <h3 style="color: #fff; margin-bottom: 5px;">🚚</h3>
            <h4>Delivery</h4>
            <p style="font-size: 0.9rem;">Fast & free shipping</p>
        </div>
        <div class="service-item">
            <h3 style="color: #fff; margin-bottom: 5px;">🛡️</h3>
            <h4>Warranty</h4>
            <p style="font-size: 0.9rem;">Official protection</p>
        </div>
        <div class="service-item">
            <h3 style="color: #fff; margin-bottom: 5px;">🎧</h3>
            <h4>Support</h4>
            <p style="font-size: 0.9rem;">24/7 Assistance</p>
        </div>
    </div>
</div>

<!-- Load GSAP & Three.js via CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<script type="module">
    import * as THREE from 'https://unpkg.com/three@0.158.0/build/three.module.js';

    // 1. Scene Setup
    const container = document.getElementById('canvas-container');
    const scene = new THREE.Scene();
    scene.background = null; // Transparent background to let CSS show

    const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 100);
    camera.position.set(0, 0, 5);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    container.appendChild(renderer.domElement);

    // 2. Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
    scene.add(ambientLight);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight.position.set(5, 5, 5);
    scene.add(directionalLight);

    const backLight = new THREE.DirectionalLight(0xffffff, 1);
    backLight.position.set(-5, -5, -5);
    scene.add(backLight);

    // 3. Create Placeholder "Phone" 3D Model
    // Since we don't have a GLB, we use a rounded box
    const phoneGroup = new THREE.Group();
    
    // Create rounded box geometry
    const width = 1.2;
    const height = 2.4;
    const depth = 0.15;
    const geometry = new THREE.BoxGeometry(width, height, depth, 4, 4, 4);
    
    const material = new THREE.MeshStandardMaterial({ 
        color: 0xe0e0e0,
        metalness: 0.8,
        roughness: 0.2,
    });
    const phoneMesh = new THREE.Mesh(geometry, material);
    
    // Add a screen
    const screenGeo = new THREE.BoxGeometry(width * 0.9, height * 0.95, depth + 0.01);
    const screenMat = new THREE.MeshStandardMaterial({
        color: 0x050505,
        metalness: 0.1,
        roughness: 0.1
    });
    const screenMesh = new THREE.Mesh(screenGeo, screenMat);
    screenMesh.position.z = 0.005;
    
    // Add camera bump
    const camGeo = new THREE.BoxGeometry(width * 0.4, width * 0.4, 0.05);
    const camMat = new THREE.MeshStandardMaterial({ color: 0x222222, metalness: 0.9, roughness: 0.3 });
    const camMesh = new THREE.Mesh(camGeo, camMat);
    camMesh.position.set(-width*0.25, height*0.35, -depth/2 - 0.02);

    phoneGroup.add(phoneMesh);
    phoneGroup.add(screenMesh);
    phoneGroup.add(camMesh);
    
    scene.add(phoneGroup);

    // Initial positioning (Hero)
    phoneGroup.position.set(1.5, 0, 0); // Moved to right side
    phoneGroup.rotation.set(0, -Math.PI / 4, 0);

    // 4. Handle Resize
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    // 5. Render Loop
    function animate() {
        requestAnimationFrame(animate);
        // Add a gentle floating animation independent of scroll
        const time = Date.now() * 0.001;
        phoneGroup.position.y += Math.sin(time) * 0.002;
        
        renderer.render(scene, camera);
    }
    animate();

    // 6. GSAP Scroll Animations
    gsap.registerPlugin(ScrollTrigger);

    // Fade in text sections when they enter viewport
    const sections = document.querySelectorAll('.section');
    sections.forEach((sec, i) => {
        gsap.to(sec, {
            opacity: 1,
            scrollTrigger: {
                trigger: sec,
                start: "top 70%",
                end: "bottom 30%",
                toggleActions: "play reverse play reverse",
            }
        });
        
        const block = sec.querySelector('.content-block');
        gsap.to(block, {
            y: 0,
            duration: 0.5,
            scrollTrigger: {
                trigger: sec,
                start: "top 70%",
                end: "bottom 30%",
                toggleActions: "play reverse play reverse",
            }
        });
    });

    // 3D Model Scroll Animations Timeline
    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: "#scroll-wrapper",
            start: "top top",
            end: "bottom bottom",
            scrub: 1,
        }
    });

    // Intro -> Camera (move to left, rotate to show back cameras)
    tl.to(phoneGroup.position, { x: -1.5, z: 1 })
      .to(phoneGroup.rotation, { x: 0.2, y: Math.PI + 0.5, z: -0.1 }, "<")
      
    // Camera -> Performance (move to right, rotate to show internals/front slightly)
      .to(phoneGroup.position, { x: 1.5, z: 0.5 })
      .to(phoneGroup.rotation, { x: -0.2, y: -Math.PI/6, z: 0.1 }, "<")
      
    // Performance -> Design (move to left, show edge profile)
      .to(phoneGroup.position, { x: -1.5, z: 0 })
      .to(phoneGroup.rotation, { x: 0, y: Math.PI/2, z: 0 }, "<")

    // Design -> Shop (fade out model as we enter static content)
      .to(phoneGroup.position, { y: 5 }, ">"); // Fly up out of view

</script>
@endsection

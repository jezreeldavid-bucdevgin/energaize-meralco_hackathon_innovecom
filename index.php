<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Font Awesome for better icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add Particles.js -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <style>
        body {
  align-items: center;
  display: flex;
  justify-content: center;
  height: 100vh;
  overflow: hidden;
}
.gegga {
  width: 0;
}
.snurra {
  filter: url(#gegga);
}
.stopp1 {
  stop-color: #f700a8;
}
.stopp2 {
  stop-color: #ff8000;
}
.halvan {
  animation: Snurra1 10s infinite linear;
  stroke-dasharray: 180 800;
  fill: none;
  stroke: url(#gradient);
  stroke-width: 23;
  stroke-linecap: round;
}
.strecken {
  animation: Snurra1 3s infinite linear;
  stroke-dasharray: 26 54;
  fill: none;
  stroke: url(#gradient);
  stroke-width: 23;
  stroke-linecap: round;
}
.skugga {
  filter: blur(5px);
  opacity: 0.3;
  position: absolute;
  transform: translate(3px, 3px);
}
@keyframes Snurra1 {
  0% {
    stroke-dashoffset: 0;
  }
  100% {
    stroke-dashoffset: -403px;
  }
}

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: transparent;
            z-index: 0;
        }
        /* Ensure content stays above particles */
        .relative-z {
            position: relative;
            z-index: 1;
        }
    </style>
    <!-- Add redirect script -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.location.href = 'landing.php';
            }, 2000); // 2000 milliseconds = 2 seconds
        }
    </script>
</head>
<body class="bg-space-black min-h-screen flex flex-col font-poppins">
    <!-- Particle Animation Container -->
    <div id="particles-js" class="fixed inset-0 z-0 pointer-events-none"></div>

    <!-- Your existing content wrapped in relative-z -->
    <div class="relative-z flex items-center justify-center h-screen">
        <svg class="gegga">
          <defs>
            <filter id="gegga">
              <feGaussianBlur in="SourceGraphic" stdDeviation="7" result="blur" />
              <feColorMatrix
                in="blur"
                mode="matrix"
                values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 20 -10"
                result="inreGegga"
              />
              <feComposite in="SourceGraphic" in2="inreGegga" operator="atop" />
            </filter>
          </defs>
        </svg>
        <svg class="snurra" width="200" height="200" viewBox="0 0 200 200">
          <defs>
            <linearGradient id="linjärGradient">
              <stop class="stopp1" offset="0" />
              <stop class="stopp2" offset="1" />
            </linearGradient>
            <linearGradient
              y2="160"
              x2="160"
              y1="40"
              x1="40"
              gradientUnits="userSpaceOnUse"
              id="gradient"
              xlink:href="#linjärGradient"
            />
          </defs>
          <path
            class="halvan"
            d="m 164,100 c 0,-35.346224 -28.65378,-64 -64,-64 -35.346224,0 -64,28.653776 -64,64 0,35.34622 28.653776,64 64,64 35.34622,0 64,-26.21502 64,-64 0,-37.784981 -26.92058,-64 -64,-64 -37.079421,0 -65.267479,26.922736 -64,64 1.267479,37.07726 26.703171,65.05317 64,64 37.29683,-1.05317 64,-64 64,-64"
          />
          <circle class="strecken" cx="100" cy="100" r="64" />
        </svg>
        <svg class="skugga" width="200" height="200" viewBox="0 0 200 200">
          <path
            class="halvan"
            d="m 164,100 c 0,-35.346224 -28.65378,-64 -64,-64 -35.346224,0 -64,28.653776 -64,64 0,35.34622 28.653776,64 64,64 35.34622,0 64,-26.21502 64,-64 0,-37.784981 -26.92058,-64 -64,-64 -37.079421,0 -65.267479,26.922736 -64,64 1.267479,37.07726 26.703171,65.05317 64,64 37.29683,-1.05317 64,-64 64,-64"
          />
          <circle class="strecken" cx="100" cy="100" r="64" />
        </svg>
    </div>

    <script>
        // Add Particles.js Configuration
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 100,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: ['#00F0FF', '#6E00FF', '#FF2D55', '#39FF14']
                },
                shape: {
                    type: 'circle'
                },
                opacity: {
                    value: 0.5,
                    random: true,
                    anim: {
                        enable: true,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: true,
                        speed: 2,
                        size_min: 0.1,
                        sync: false
                    }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#A000C6',
                    opacity: 0.2,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: true,
                    straight: false,
                    out_mode: 'out',
                    bounce: false,
                    attract: {
                        enable: true,
                        rotateX: 600,
                        rotateY: 1200
                    }
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'grab'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 140,
                        line_linked: {
                            opacity: 0.5
                        }
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>
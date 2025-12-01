<?php
// gallery.php
// Server-side: scan the gallery directory and output a JS array used by the existing front-end.
// Put images/videos into ./gallery/ (relative to this file).
function human_date($timestamp) {
    return date('Y-m-d', $timestamp);
}

$dir = __DIR__ . '/gallery';
$allowed_image_ext = ['jpg','jpeg','png','gif','webp','avif'];
$allowed_video_ext = ['mp4','webm','ogg'];

$items = [];

if (is_dir($dir)) {
    $files = scandir($dir);
    foreach ($files as $f) {
        if ($f[0] === '.') continue; // skip hidden
        $path = $dir . '/' . $f;
        if (!is_file($path)) continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        $mtime = filemtime($path);
        $item = [
            'src' => 'gallery/' . rawurlencode($f),
            'date' => human_date($mtime),
            'caption' => pathinfo($f, PATHINFO_FILENAME),
        ];
        if (in_array($ext, $allowed_image_ext)) {
            $item['type'] = 'image';
        } elseif (in_array($ext, $allowed_video_ext)) {
            $item['type'] = 'video';
            // optional: if there is a poster image with same name + .jpg, use it
            $posterCandidate = $dir . '/' . pathinfo($f, PATHINFO_FILENAME) . '.jpg';
            if (file_exists($posterCandidate)) {
                $item['poster'] = 'gallery/' . rawurlencode(pathinfo($f, PATHINFO_FILENAME) . '.jpg');
            }
        } else {
            continue; // skip unknown types
        }
        $items[] = $item;
    }
    // sort newest-first by default
    usort($items, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Khadim ul Safar</title>
    
    <!-- External CSS File (optional) -->
    <link rel="stylesheet" href="styles.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#071739', // Navy Blue
                        secondary: '#f7c66e', // Gold
                        accent: '#ffffff', // White
                    },
                    fontFamily: {
                        heading: ['Amiri', 'serif'],
                        body: ['Lato', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-body bg-white text-gray-800">

    <!-- Navigation -->
     <nav class="bg-primary text-white border-b-4 border-secondary shadow-md fixed w-full z-50 transition-all duration-300" id="navbar" >
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24">
                <div class="flex items-center">
                    <a href="#" class="flex items-center gap-2">
                        <img src="assets/kslogo.jpg" alt="Khadim ul Haram" class="h-24 w-auto">
                        <!-- <span class="font-heading text-2xl font-bold text-gray-800">KHADIM ul <span class="text-secondary">HARAM</span></span> -->
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="index.html#home" class="relative px-4 py-2 rounded-full transition duration-300 hover:bg-secondary hover:text-white">Home</a>
                    <a href="index.html#services" class="relative px-4 py-2 rounded-full transition duration-300 hover:bg-secondary hover:text-white">Services</a>
                    <a href="index.html#packages" class="relative px-4 py-2 rounded-full transition duration-300 hover:bg-secondary hover:text-white">Packages</a>
                    <a href="index.html#about" class="relative px-4 py-2 rounded-full transition duration-300 hover:bg-secondary hover:text-white">About Us</a>
                    <a href="gallery.php" class="relative px-4 py-2 rounded-full transition duration-300 hover:bg-secondary hover:text-white">Gallery</a>
                    <a href="index.html#contact" class="bg-accent text-secondary px-5 py-2 rounded-full hover:bg-secondary transition shadow-lg hover:text-accent transform hover:-translate-y-0.5">Book Now</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-secondary hover:text-white focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden md:hidden bg-primary border-t">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="index.html#home" class="block px-3 py-2 text-secondary hover:text-primary hover:bg-white rounded-md">Home</a>
                <a href="index.html#services" class="block px-3 py-2 text-secondary hover:text-primary hover:bg-white rounded-md">Services</a>
                <a href="index.html#packages" class="block px-3 py-2 text-secondary hover:text-primary hover:bg-white rounded-md">Packages</a>
                <a href="index.html#about" class="block px-3 py-2 text-secondary hover:text-primary hover:bg-white rounded-md">About Us</a>
                <a href="gallery.php" class="block px-3 py-2 text-secondary hover:text-primary hover:bg-white rounded-md">Gallery</a>
                <a href="index.html#contact" class="block px-3 py-2 text-secondary font-bold hover:text-primary hover:bg-secondary rounded-md">Book Now</a>
            </div>
        </div>
    </nav>
    

    <!-- Header -->
    <header class="bg-primary text-white pt-32 pb-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="font-heading text-4xl md:text-5xl font-bold mb-4">Our Journey</h1>
            <p class="text-secondary text-lg max-w-2xl mx-auto">Moments captured from the sacred lands of Makkah and Madinah.</p>
        </div>
    </header>

        
    <section class="py-12">
        <div class="container mx-auto px-4">
            
            <!-- Controls -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                <div class="flex gap-2">
                    <button class="filter-btn active px-4 py-2 rounded-full bg-primary text-white text-sm font-bold shadow-md hover:text-white hover:bg-primary transition" data-filter="all">All</button>
                    <button class="filter-btn px-4 py-2 rounded-full bg-primary text-white text-sm font-bold shadow-sm hover:text-white hover:bg-primary transition" data-filter="video">Videos</button>
                    <button class="filter-btn px-4 py-2 rounded-full bg-primary text-white text-sm font-bold shadow-sm hover:text-white hover:bg-primary transition" data-filter="image">Photos</button>
                </div>
                
                <div class="flex items-center gap-2">
                    <label class="text-sm font-bold text-gray-600">Sort by:</label>
                    <select id="sortSelect" class="px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="newest">Date (Newest First)</option>
                        <option value="oldest">Date (Oldest First)</option>
                    </select>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div id="gallery-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Items injected by JS (populated from PHP) -->
            </div>

        </div>
    </section>

    <!-- Footer (Simplified) -->
    <footer class="bg-primary text-white py-8 border-t-4 border-secondary text-center">
        <p class="text-sm text-gray-400">&copy; <?php echo date('Y'); ?> Khadim ul Haram. <a href="index.html" class="text-secondary hover:underline">Back to Home</a></p>
    </footer>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="fixed inset-0 z-[100] hidden bg-black bg-opacity-95 flex items-center justify-center p-4">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-4xl hover:text-secondary">&times;</button>
        <div id="lightbox-content" class="max-w-5xl max-h-full w-full flex justify-center items-center">
            <!-- Content injected here -->
        </div>
    </div>

    <script>
        // ==========================================
        // GALLERY DATA: injected from PHP
        // ==========================================
        const galleryData = <?php echo json_encode($items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;

        // ==========================================
        // LOGIC CODE (same as original, unchanged)
        // ==========================================
        const grid = document.getElementById('gallery-grid');
        const sortSelect = document.getElementById('sortSelect');
        const filterBtns = document.querySelectorAll('.filter-btn');
        let currentFilter = 'all';

        // Initial Render
        renderGallery();

        // Event Listeners
        sortSelect.addEventListener('change', renderGallery);
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                // Update visual state
                filterBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-white');
                    b.classList.add('bg-secondary', 'text-primary');
                });
                e.target.classList.remove('bg-secondary', 'text-primary');
                e.target.classList.add('bg-primary', 'text-white');
                
                // Filter logic
                currentFilter = e.target.dataset.filter;
                renderGallery();
            });
        });

        function renderGallery() {
            grid.innerHTML = '';
            
            // 1. Filter
            let filteredData = galleryData.filter(item => {
                if (currentFilter === 'all') return true;
                return item.type === currentFilter;
            });

            // 2. Sort
            const sortOrder = sortSelect.value;
            filteredData.sort((a, b) => {
                const dateA = new Date(a.date);
                const dateB = new Date(b.date);
                return sortOrder === 'newest' ? dateB - dateA : dateA - dateB;
            });

            // 3. Display
            if (filteredData.length === 0) {
                grid.innerHTML = `<div class="col-span-full text-center py-10 text-gray-500">No items found.</div>`;
                return;
            }

            filteredData.forEach(item => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 group cursor-pointer';
                
                let mediaContent = '';
                let icon = '';

                if (item.type === 'image') {
                    mediaContent = `<img src="${item.src}" alt="${item.caption}" class="w-full h-64 object-cover group-hover:scale-105 transition duration-700">`;
                    icon = `<i class="fa-solid fa-image"></i>`;
                } else {
                    // For videos in grid, we show a poster or a container
                    mediaContent = `
                        <div class="relative w-full h-64 bg-black group-hover:scale-105 transition duration-700">
                             ${item.poster ? `<img src="${item.poster}" class="w-full h-full object-cover opacity-60">` : ''}
                             <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-12 h-12 bg-white/80 rounded-full flex items-center justify-center pl-1">
                                    <i class="fa-solid fa-play text-primary text-xl"></i>
                                </div>
                             </div>
                        </div>`;
                    icon = `<i class="fa-solid fa-video"></i>`;
                }

                card.innerHTML = `
                    <div class="relative overflow-hidden" onclick="openLightbox('${item.src}', '${item.type}')">
                        ${mediaContent}
                        <div class="absolute top-3 right-3 bg-white/90 px-3 py-1 rounded-full text-xs font-bold text-primary shadow-sm flex items-center gap-2">
                            ${icon} ${new Date(item.date).toLocaleDateString()}
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-gray-700 font-bold">${item.caption}</p>
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        // Lightbox Functions
        const lightbox = document.getElementById('lightbox');
        const lightboxContent = document.getElementById('lightbox-content');

        function openLightbox(src, type) {
            lightbox.classList.remove('hidden');
            if (type === 'image') {
                lightboxContent.innerHTML = `<img src="${src}" class="max-h-[85vh] rounded-lg shadow-2xl">`;
            } else {
                lightboxContent.innerHTML = `
                    <video controls autoplay class="max-h-[85vh] max-w-full rounded-lg shadow-2xl">
                        <source src="${src}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>`;
            }
        }

        function closeLightbox() {
            lightbox.classList.add('hidden');
            lightboxContent.innerHTML = '';
        }

        // Mobile Menu (Reused logic)
        document.getElementById('mobile-menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>

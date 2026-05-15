#!/usr/bin/env node

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

// Ambil argumen nama project dari command line
const projectName = process.argv[2];

if (!projectName) {
    console.error("Silakan tentukan nama project. Contoh: npx create-aero my-project");
    process.exit(1);
}

const currentPath = process.cwd();
const projectPath = path.join(currentPath, projectName);

if (fs.existsSync(projectPath)) {
    console.error(`Folder '${projectName}' sudah ada. Silakan gunakan nama lain atau hapus folder tersebut.`);
    process.exit(1);
}

// Path source framework (direktori root dari package npm yang sedang dieksekusi npx)
const sourcePath = path.join(__dirname, '..');

// Daftar file dan folder yang tidak disalin ke project pengguna
const ignoreList = ['node_modules', '.git', 'bin', 'package-lock.json', '.npmignore', '.env'];

// Fungsi rekursif untuk copy file & folder
function copyFolderSync(from, to) {
    if (!fs.existsSync(to)) {
        fs.mkdirSync(to, { recursive: true });
    }
    
    fs.readdirSync(from).forEach(element => {
        if (ignoreList.includes(element)) return;
        
        const fromPath = path.join(from, element);
        const toPath = path.join(to, element);
        const stat = fs.lstatSync(fromPath);
        
        if (stat.isFile()) {
            fs.copyFileSync(fromPath, toPath);
            // Otomatis membuatkan file .env dari .env_example untuk user
            if (element === '.env_example') {
                fs.copyFileSync(fromPath, path.join(to, '.env'));
            }
        } else if (stat.isDirectory()) {
            copyFolderSync(fromPath, toPath);
        }
    });
}

console.log(`\nMembuat project Aero SPA baru di ${projectPath}...`);
copyFolderSync(sourcePath, projectPath);

// Update package.json di folder hasil agar bersih
const targetPackageJsonPath = path.join(projectPath, 'package.json');
if (fs.existsSync(targetPackageJsonPath)) {
    const packageData = JSON.parse(fs.readFileSync(targetPackageJsonPath, 'utf8'));
    // Ubah nama project sesuai input pengguna
    packageData.name = projectName;
    packageData.version = "1.0.0";
    packageData.description = "Project berbasis Aero Framework";
    // Hapus script cli/bin karena project pengguna bukan installer
    if (packageData.bin) {
        delete packageData.bin;
    }
    
    fs.writeFileSync(targetPackageJsonPath, JSON.stringify(packageData, null, 2));
}

console.log('Menginstall dependensi (Tailwind, dll)...');
try {
    process.chdir(projectPath);
    // Jalankan npm install di folder project yang baru dibuat
    execSync('npm install', { stdio: 'inherit' });
} catch (error) {
    console.error('Gagal menginstall dependensi otomatis. Silakan jalankan "npm install" secara manual nanti.');
}

console.log('\nProject berhasil dibuat!');
console.log('--------------------------------------------------');
console.log('Langkah selanjutnya:');
console.log(`  1. cd ${projectName}`);
console.log('  2. Sesuaikan konfigurasi di file .env');
console.log('  3. Jalankan server PHP (di terminal 1):');
console.log('       php -S localhost:8000 -t public');
console.log('  4. Jalankan build CSS (di terminal 2):');
console.log('       npm run css:watch');
console.log('--------------------------------------------------\n');

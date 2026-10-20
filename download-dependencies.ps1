# Script to download Composer dependencies manually using Windows curl
# Bypasses SSL issues by using curl with --insecure flag

# Configuration
$projectPath = "C:\Users\SOP\Documents\X2\Projet\developpement web"
$vendorPath = Join-Path $projectPath "vendor"
$tempPath = Join-Path $projectPath "temp_downloads"
$composerLock = Join-Path $projectPath "composer.lock"

# Create necessary folders
if (-not (Test-Path $vendorPath)) {
    New-Item -ItemType Directory -Path $vendorPath -Force | Out-Null
    Write-Host "Vendor folder created" -ForegroundColor Green
}

if (-not (Test-Path $tempPath)) {
    New-Item -ItemType Directory -Path $tempPath -Force | Out-Null
    Write-Host "Temp downloads folder created" -ForegroundColor Green
}

# Read composer.lock file
Write-Host "Reading composer.lock file..." -ForegroundColor Yellow
$lockContent = Get-Content $composerLock -Raw
$lockJson = $lockContent | ConvertFrom-Json

# Download packages
$packages = $lockJson.packages + $lockJson.'packages-dev'
$totalPackages = $packages.Count
$currentPackage = 0

Write-Host "Downloading $totalPackages packages..." -ForegroundColor Yellow

foreach ($package in $packages) {
    $currentPackage++
    $packageName = $package.name
    $packageVersion = $package.version
    $distUrl = $package.dist.url
    
    Write-Host "[$currentPackage/$totalPackages] Downloading $packageName ($packageVersion)..." -ForegroundColor Cyan
    
    try {
        # Zip filename
        $zipFileName = $packageName -replace '/', '_'
        $zipPath = Join-Path $tempPath "$zipFileName.zip"
        
        # Download file using Windows curl with --insecure to bypass SSL
        curl.exe --insecure --location --user-agent "Mozilla/5.0 (Windows NT 10.0; Win64; x64)" --output $zipPath $distUrl
        
        # Check if download succeeded
        if (-not (Test-Path $zipPath)) {
            throw "Download failed - file not created"
        }
        
        # Determine destination folder
        $vendorPackagePath = Join-Path $vendorPath $packageName
        
        # Create package folder
        if (-not (Test-Path $vendorPackagePath)) {
            New-Item -ItemType Directory -Path $vendorPackagePath -Force | Out-Null
        }
        
        # Extract zip
        Add-Type -AssemblyName System.IO.Compression.FileSystem
        [System.IO.Compression.ZipFile]::ExtractToDirectory($zipPath, $tempPath)
        
        # Find extracted folder and move its contents
        $extractedFolders = Get-ChildItem $tempPath -Directory | Where-Object { $_.Name -ne $zipFileName }
        foreach ($folder in $extractedFolders) {
            if ($folder.Name -match "^[a-z0-9-]+$") {
                # Copy extracted folder contents to vendor folder
                Get-ChildItem $folder.FullName -Recurse | Move-Item -Destination $vendorPackagePath -Force
                Remove-Item $folder.FullName -Recurse -Force
                break
            }
        }
        
        # Delete zip file
        Remove-Item $zipPath -Force
        
        Write-Host "  OK $packageName downloaded successfully" -ForegroundColor Green
    }
    catch {
        Write-Host "  ERROR Failed to download $packageName : $_" -ForegroundColor Red
    }
}

# Cleanup temp folder
Write-Host "Cleaning up temp folder..." -ForegroundColor Yellow
Remove-Item $tempPath -Recurse -Force

Write-Host "Download complete!" -ForegroundColor Green
Write-Host "Please now run: php composer.phar dump-autoload" -ForegroundColor Yellow

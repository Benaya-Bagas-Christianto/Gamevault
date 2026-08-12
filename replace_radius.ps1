$content = Get-Content -Path public/css/app.css -Raw
$newContent = [regex]::Replace($content, '(?i)border-radius\s*:\s*[^;""'']+([;]|(?=[""'']))', 'border-radius: 0$1')
if ($content -cne $newContent) {
    Set-Content -Path public/css/app.css -Value $newContent -NoNewline
}

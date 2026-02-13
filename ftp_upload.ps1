# FTP Upload Script
$ftpServer = "ftp://cookshaheen.com"
$username = "u848579859"
$password = Read-Host "Enter FTP password" -AsSecureString
$credential = New-Object System.Net.NetworkCredential($username, $password)

# Create FTP Web Request
$files = @("deploy_halwa.php", "gajar-halwa.png")

foreach ($file in $files) {
    $filePath = "c:/Users/Syed_Parvez/cookshaheen.com/$file"
    $ftpUri = "$ftpServer/public_html/$file"
    
    $ftpRequest = [System.Net.FtpWebRequest]::Create($ftpUri)
    $ftpRequest.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
    $ftpRequest.Credentials = $credential
    $ftpRequest.UseBinary = $true
    $ftpRequest.UsePassive = $true
    
    # Read file and upload
    $fileContent = [System.IO.File]::ReadAllBytes($filePath)
    $ftpRequest.ContentLength = $fileContent.Length
    
    $requestStream = $ftpRequest.GetRequestStream()
    $requestStream.Write($fileContent, 0, $fileContent.Length)
    $requestStream.Close()
    
    $response = $ftpRequest.GetResponse()
    Write-Host "Uploaded $file - Status: $($response.StatusDescription)"
    $response.Close()
}

Write-Host "All files uploaded successfully!"

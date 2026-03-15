[Setup]
AppName=Job Portal
AppVersion=1.0
AppPublisher=Job Portal
DefaultDirName={pf}\JobPortal
DefaultGroupName=Job Portal
OutputBaseFilename=JobPortalInstaller
SetupIconFile=C:\xampp\htdocs\job\softwareApp\favicon_io\favicon.ico
OutputDir=.
Compression=lzma
SolidCompression=yes

[Files]
Source: "C:\xampp\htdocs\job\softwareApp\main.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "C:\xampp\htdocs\job\softwareApp\favicon_io\*"; DestDir: "{app}\favicon_io"; Flags: recursesubdirs createallsubdirs ignoreversion

[Icons]
Name: "{group}\Job Portal"; Filename: "{app}\main.exe"; IconFilename: "{app}\favicon_io\favicon.ico"
Name: "{userdesktop}\Job Portal"; Filename: "{app}\main.exe"; IconFilename: "{app}\favicon_io\favicon.ico"

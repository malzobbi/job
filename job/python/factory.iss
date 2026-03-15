[Setup]
AppName=Job Portal Tools
AppVersion=1.0
DefaultDirName={pf}\JobPortal
DefaultGroupName=Job Portal
OutputDir=.

[Files]
Source: "C:\xampp\htdocs\job\softwareApp\main.exe"; DestDir: "{app}"; Flags: ignoreversion
Source: "C:\xampp\htdocs\job\softwareApp\favicon_io\*"; DestDir: "{app}\favicon_io"; Flags: recursesubdirs createallsubdirs ignoreversion

[Icons]
Name: "{group}\Job Portal Login"; Filename: "{app}\login.exe"; IconFilename: "C:\xampp\htdocs\job\softwareApp\favicon_io\favicon.ico"
Name: "{userdesktop}\Job Portal Login"; Filename: "{app}\login.exe"; IconFilename: "C:\xampp\htdocs\job\softwareApp\favicon_io\favicon.ico"

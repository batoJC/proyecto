@echo off
setlocal
echo What do you do?
set/p task=:
set "var1=%task:-="^&REM #%
set "var2=%task:*-=%"

IF "%var1%"=="s" goto init    
IF "%var1%"=="p" goto push
IF "%var1%"=="c" goto commit

%task%
goto exit

:init
    \xampp\xampp_start.exe
    start php artisan serve
    code .
    http://127.0.0.1:8000
    goto exit

:push
    git push
    goto exit

:commit
    echo  Doing commit
    git add .
    git commit -m "%var2%"
    goto exit


:exit
    pause
    cls
    auto.bat
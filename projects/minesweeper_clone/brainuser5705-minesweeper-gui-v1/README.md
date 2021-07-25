# minesweeper-gui
 **A simple JavaFX GUI of the Minesweeper game**  
 *v1.0*   
 *built by Ashley Liew*

----

## Version 1 Features
- layout similar to typical Minesweeper game
- new game button, game over modes, different sizes and densities available
- only basic commands (reveal, flag, unflag)

----

## Instructions on how to execute JAR file
#### 1) You must have the following installed on your computer:
- [Java Runtime Environment](https://www.oracle.com/java/technologies/javase-jdk15-downloads.html)
- [JavaFX](https://openjfx.io/)

#### 2) Running the file
- Install the necessary software above.
- Take note of the directory path where you install JavaFX, specifically the `lib` folder.
- In your command line, go to the directory where you download the JAR file.
- Type in this command: `java --module-path` _path-to-javafx-lib_ `--add-modules javafx.controls,javafx.graphics -jar MinesweeperGUI.jar` (view footnote)
- Press 'Enter' and play!

*_Example_: Let's say the path to your JavaFX lib file is: `C:\javafx\javafx-sdk-11.0.2\lib`*    
*The command you will put in will be: `java --module-path \javafx\javafx-sdk-11.0.2\lib --add-modules javafx.controls,javafx.graphics -jar MinesweeperGUI.jar`*

## Notes on Implementation
I used recursion to implement Minesweeper which limits the possible dimension sizes due to stack overflow for large sizes (80-90+ rows and columns). The grids will not reveal and the command line where you write the program will display an error.

## Credits
All graphics *(not including the JavaFX components)* are made by me, except for the ‘7’ icon for the numbered cell, Rosé pictures on the new game button *(which were sourced from Google)*, and the flag icon *(credit below)*.

- "File:HistoryIcon.svg" by Ludvig14 is licensed with CC BY-SA 3.0. To view a copy of this license, visit https://creativecommons.org/licenses/by-sa/3.0






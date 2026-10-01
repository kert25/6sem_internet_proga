@echo off

title LR6-XSD-validation

javac ValidateXsd.java

java ValidateXsd Videoteka.xml Videoteka.xsd

java ValidateXsd invalid/invalid-age-rating.xml Videoteka.xsd

java ValidateXsd invalid/invalid-duration.xml Videoteka.xsd

java ValidateXsd invalid/invalid-director-reference.xml Videoteka.xsd

echo.

echo Validation run complete.

pause


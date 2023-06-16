echo "*** CLEANUP UTILITY STARTED ***"
echo "WARNING: This will delete all files in datasets/ directory, followed by emptying the log file, followed by emptying the metadata catalog."
echo "This should be only used for testing purposes on a local server. IT WILL CAUSE DATA LOSS."
echo "Are you sure? Enter the following 'iwishtodestroymydata'"
read INPUT_STRING
echo "You entered: " $INPUT_STRING

if [ "$INPUT_STRING" = "iwishtodestroymydata" ] 
then
    rm -rf ./datasets/
    rm log.txt
    rm dataset-catalog.csv
    mkdir ./datasets/
    touch log.txt
    touch dataset-catalog.csv
    echo "name,year,description,sdg,filename" > dataset-catalog.csv
    echo "*** CLEANUP FINISHED ***"
else
    echo "*** NO CLEANUP DONE ***"
fi
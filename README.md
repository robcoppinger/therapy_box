# therapy_box
test project for Therapy Box recruitment. The site is live at www.robcopp.com

# What has been implemented

### News
Connects to the BBC RSS feed to display the most recent news item - Heading, description and image

### Weather
The weather preview connects to the openweathermap.org API, retrieving the current, min and max temperature for the day, weather description, as well as the icon for the weather conditions. HTML's geolocator is used to get the location of the user, with a default location set if location could not be established

## Sport
The sport preview connects to the BBC Sport RSS feed, displaying title and description of the most recent news item.

The sports.php page reads a [csv file](http://www.football-data.co.uk/mmz4281/1718/I1.csv) containing football results. All teams are displayed, and when a team is clicked on or searched, it returns the names of the teams that the specified team has beaten in the season.

## Photo Gallery

The photo gallery preview displays photos that the user has uploaded

The gallery.php page allows users to view the images, as well as upload new ones. 
NOTE: Current hosting has a file upload limit, so if an image fails, try a smaller one

## Task List
The task list preview shows a list of uncompleted tasks that the user has defined. From here, a task can be marked as complete.

The tasks.php page displays all tasks added by the user, completed as well as uncompleted, allows the user to toggle the status between "complete" and "uncomplete", and allows the user to add new tasks

## Favourite Warmer

This connects to a [JSON file](https://therapy-box.co.uk/hackathon/clothing-api.php?username=swapnil) containing individual entries of what item of clothing a person was wearing each day. This JSON file is then read, processed, and a chart is rendered using FusionCharts to displat the results

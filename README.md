# Image Organizer

## Description

Multi-user app that allows users to upload images and organize them into categories and choices in order to filter through them.

## Technologies

HTML, CSS, JavaScript, PHP, MySQL, jQuery, Docker

## Demo

https://youtu.be/z_rzw-_6lPw

## Build

Docker image hosted on Docker Hub

https://hub.docker.com/r/mjbelow/image-organizer

Base Docker Image

https://hub.docker.com/r/tomsik68/xampp/

Create Docker container (use whatever ports that are available)

`docker run -d -p 41061:22 -p 41062:80 mjbelow/image-organizer`

## App

* Initialize Database: [http://localhost:41062/www/init_db.php](http://localhost:41062/www/init_db.php)
* Main: [http://localhost:41062/www/](http://localhost:41062/www/)
  * Username: mjbelow
  * Password: pass

## DTU Feedback API

This is an api for the DTUFeedbackAndroid application 

## Installation

* Clone this project
* Create the database named `dtu-feedback` (look for the info in the
`.env` file)
* Run `php artisan migrate --seed` to create all the tables and seed the database
* Run `php artisan jwt:generate` (You have to run it again every time
you reset the database)
* Add the key to the TODO (1) (in the `.env` file)

## Note about images

All the images are saved in the `storage/app/images/` dir

## Contributors
* Le Dinh Nhat Khanh
* Lu Thanh Vinh
* Ly Bao Khanh
* Nguyen Tung Duong
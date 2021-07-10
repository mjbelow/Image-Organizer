FROM tomsik68/xampp:8

WORKDIR /www

COPY . .

RUN chmod 777 images

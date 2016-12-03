FROM wordpress
RUN apt-get update && apt-get -y install php5-mcrypt
RUN php5enmod mcrypt
RUN cp /usr/lib/php5/20131226/mcrypt.so /usr/local/lib/php/extensions/no-debug-non-zts-20131226/
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]

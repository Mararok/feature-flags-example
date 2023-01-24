FROM php:8.1.14-cli-alpine

# crond needs root, so install dcron and cap package and set the capabilities
# on dcron binary https://github.com/inter169/systs/blob/master/alpine/crond/README.md
RUN apk add --no-cache dcron procps
RUN echo "* * * * * /bin/bash echo 1" >> /var/spool/cron/crontabs/root
USER www-data

COPY ./t.sh ./t.sh
ENTRYPOINT ["./t.sh"]

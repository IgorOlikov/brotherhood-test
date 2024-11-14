FROM postgres:16-alpine3.18


COPY ./postgresql/script/init-db.sh /docker-entrypoint-initdb.d/init-db.sh

RUN chmod +x /docker-entrypoint-initdb.d/init-db.sh

ENTRYPOINT ["bash", "-c", "docker-entrypoint.sh postgres & /docker-entrypoint-initdb.d/init-db.sh"]
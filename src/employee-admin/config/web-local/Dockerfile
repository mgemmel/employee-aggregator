FROM node:16.3.0 as builder

RUN apt-get update
RUN apt-get install nano

WORKDIR /usr/src/employee-admin
COPY ./src/package*.json ./

RUN npm install

FROM node:16.3.0 as production

ARG env_type
ENV ENV_TYPE $env_type

WORKDIR /usr/src/employee-admin
COPY --from=builder /usr/src/employee-admin/node_modules ./node_modules
COPY ./src .

RUN chmod -R 777 ./node_modules

EXPOSE 9000

CMD ["npm", "run", "serve"]

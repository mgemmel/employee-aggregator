FROM node:16.3.0 as builder

RUN apt-get update
RUN apt-get install nano

WORKDIR /usr/src/emploee-admin
COPY ./src/package*.json ./

RUN npm install

FROM node:16.3.0 as production

ARG env_type
ENV ENV_TYPE $env_type

WORKDIR /usr/src/emploee-admin
COPY --from=builder /usr/src/emploee-admin/node_modules ./node_modules
COPY ./src .

#COPY ./src/.env.$env_type ./.env
#COPY ./src/env.json.$env_type ./env.json
EXPOSE 9000

CMD ["npm", "run", "serve"]

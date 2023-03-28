FROM node:latest

WORKDIR /var/www/frontend

COPY ./frontend /var/www/frontend

RUN npm install --save --legacy-peer-deps
RUN npm install vite

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0","--port","8000"]
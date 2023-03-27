FROM node:lts-alpine

# устанавливаем простой HTTP-сервер для статики
#RUN npm install -g http-server --save

# делаем каталог 'app' текущим рабочим каталогом
WORKDIR /var/www/frontend

COPY ./frontend /var/www/frontend
# копируем оба 'package.json' и 'package-lock.json' (если есть)
#COPY ./frontend/package*.json ./var/www/frontend

# устанавливаем зависимости проекта
RUN npm install --save --legacy-peer-deps
RUN npm install vite

# копируем файлы и каталоги проекта в текущий рабочий каталог (т.е. в каталог 'app')
#COPY . .

# собираем приложение для production с минификацией
#RUN npm run start

#EXPOSE 8000
#CMD npm run dev -- --host 0.0.0.0 --port 8000

#CMD ["npm", "run", "dev"]
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0","--port","8000"]
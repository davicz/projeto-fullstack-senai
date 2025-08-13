FROM node:18-alpine As development

WORKDIR /app

COPY . .

RUN npm install

RUN npm install -g @angular/cli

COPY package*.json ./

EXPOSE 4200

CMD ["ng", "serve", "--host", "0.0.0.0"]


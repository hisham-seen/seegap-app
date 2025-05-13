FROM nginx:alpine

COPY default.conf /etc/nginx/conf.d/default.conf
COPY domain.conf /etc/nginx/conf.d/domain.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]

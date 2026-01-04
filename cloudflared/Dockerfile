FROM cloudflare/cloudflared:latest

RUN apt update && apt install -y curl && \
    apt clean && rm -rf /var/lib/apt/lists/*

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

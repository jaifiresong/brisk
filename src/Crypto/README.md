# 生成 RSA 私钥

```shell
openssl genrsa -out rsa_private_key.pem
```

# 生成 RSA 公钥

```shell
openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem
```
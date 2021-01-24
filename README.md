## 使用方法
1. 进入docker目录执行以下命令

```shell
docker build -t code_standard .
```
2. 进入容器,先将`YOUR_SSH_DIR`和`YOUR_CODE_DIR`替换为你本地路径，然后执行一下命令

```shell
docker run -it -v YOUR_SSH_DIR:/root/.ssh -v YOUR_CODE_DIR:/usr/share/nginx/html --rm --name code_standard code_standard
```

3. 进入容器后在项目目录下执行 `vcs`
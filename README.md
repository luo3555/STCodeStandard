## 使用方法
1. 构建容器，进入到 `STCodeStandard/docker` 目录下，执行以下代码：

```shell
sudo docker build  --build-arg USERNAME="GIT_USE_NAME" --build-arg EMAIL="GIT_USE_EMAIL" --build-arg SSH_PRV_KEY="$(cat /home/YOUR_ACCOUNT/.ssh/YOUR_PRIVATE_KEY_FILE_NAME)" -t code_standard .
```

**注意**
替换字符|描述
------------|-----------
GIT_USE_NAME|git config --global user.name 使用的 Name
GIT_USE_EMAIL|git config --global user.email 使用的 Email
YOUR_ACCOUNT|是你当前的用户名，因为 Docker 是在 root 下执行，所以这里要写你用户文件夹的绝对路径
YOUR_PRIVATE_KEY_FILE_NAME|Git push 用到的私钥

2. 进入容器，在自己用户根目录下找到 `.bashrc` 文件，然后在最末尾添加以下方法。

```shell
function vcs() {
    sudo docker run -it -v YOUR_CODE_DIR:/usr/share/nginx/html --rm --name code_standard code_standard
}
```

替换字符|描述
------------|-----------
YOUR_CODE_DIR|你自己的代码文件夹路径

在执行 `source ~/.bashrc`

现在你可以T通过命令行直接执行 `vcs` 进入到代码检测容器了


***

##代码检测
1. 单个文件或文件夹检测

```shell
fix 文件或文件夹
```
2. 本次修改批量检测, 项目文件夹下执行(validate code standard)

```
vcs
```
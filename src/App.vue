<style>
body {
  background-color: #fcfcfc;
  font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB",
    "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
  line-height: 1.5;
}
</style>

<template>
  <div>
    <menuNav />
    <div class="mdui-container">
      <keep-alive>
        <router-view></router-view>
      </keep-alive>
      <div class="mdui-row">
        <p style="text-align: center">
          msdfc.
          <a href="https://kennen0.github.io">kennen0.github.io</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import mdui from "mdui";
/* import "mdui/dist/css/mdui.css"; */
import menuNav from "@/components/menuNav";
export default {
  name: "App",
  data() {
    return { hideSider: false };
  },
  components: { menuNav },
  /*     methods: {
    openDrawer() {
      var drawer = new mdui.Drawer("#main-drawer");
      drawer.open();
    }
  }, */
  mounted: function() {
    this.GLOBAL.mdui = mdui;
    //更新数据
    this.GLOBAL.getInfo = () => {
      var req = {};
      if (!this.GLOBAL.infoLoaded) {
        this.$message.warning(`正在加载成员信息，第一次加载慢请耐心等待。。。`);
      }
      /* 请求 获取同步信息 update */
      axios({
        url: this.GLOBAL.api.update,
        method: "post",
        headers: new this.GLOBAL.headers(),
        data: req
      })
        .then(response => {
          this.GLOBAL.upInfo(response.data, req);
        })
        .catch(e => {
          console.log(e);
        });
    };
    this.GLOBAL.upInfo = (res, req) => {
      if (!this.GLOBAL.infoLoaded) {
        this.$message.success(`成员信息加载完毕，现在可以使用了！`);
      } else {
        this.$message.success(`成员信息更新成功！`);
      }
      this.GLOBAL.info = res.content;
      this.GLOBAL.saveInfo();
      this.GLOBAL.infoLoaded = true;
      console.log(res, req);
    };
    this.GLOBAL.getToken = (username, password) => {
      var req = { mobile: username + "", pwd: password + "" };
      axios({
        url: this.GLOBAL.api.login,
        method: "post",
        headers: new this.GLOBAL.headers(true),
        data: req
      })
        .then(response => {
          this.GLOBAL.upToken(response.data, req);
        })
        .catch(e => {
          console.log(e);
        });
    };
    this.GLOBAL.upToken = (res, req) => {
      if (res.success) {
        this.$message.success(`登录成功！`);
        /* 统计 登录用户 */
        this.GLOBAL.sta("loginRES", res);
        /* 回调 登录获取token */
        this.$set(this.GLOBAL, 'account', res.content);
        // this.GLOBAL.account = res.content;
        this.GLOBAL.accountSave();
        console.log(res, req);
      } else {
        this.$message.error(`登录失败= =! ${res.status} ${res.message}`);
      }
    };
    if (this.GLOBAL.config.isAutoSync) {
      this.GLOBAL.getInfo();
      if (this.GLOBAL.config.spass) {
        // 使用保存的密码获取token
        this.GLOBAL.getToken(this.GLOBAL.config.smobile, this.GLOBAL.config.spass);
      }
    }
  }
};
</script>
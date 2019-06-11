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
    <menuNav/>
    <div class="mdui-container">
      <keep-alive>
        <router-view></router-view>
      </keep-alive>
      <div class="mdui-row">
        <p style="text-align: center">
          M S D F C
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

    if (this.GLOBAL.config.isAutoSync) {
      this.GLOBAL.getInfo();
    }
  }
};
</script>
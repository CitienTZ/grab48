<template>
  <div>
    <h1># 账户</h1>
    <cDivider />
    <div>
      <p>
        当前状态: {{status?('已登录'):('未登录')}}
        <el-button v-show="status" type="success" @click="getPunch" round>打卡签到</el-button>
        <el-tooltip :content="GLOBAL.account.token" placement="bottom" effect="light">
          <el-button v-show="status" type="primary" round>显示token</el-button>
        </el-tooltip>
        <el-button v-show="status" type="danger" @click="delToken" round>删除token及保存的密码</el-button>
      </p>
    </div>
    <cDivider />
    <div v-if="GLOBAL.account.userInfo">
      <InfoUser :item="GLOBAL.account" />
      <cDivider />
    </div>
    <el-form ref="form" label-width="80px">
      <el-form-item label="用户名">
        <el-input placeholder="请输入用户名(手机号)" @keyup.enter.native="getToken" v-model="username"></el-input>
      </el-form-item>
      <el-form-item label="密码">
        <el-input
          type="password"
          @keyup.enter.native="getToken"
          placeholder="请输入密码"
          v-model="password"
        ></el-input>
      </el-form-item>
      <el-form-item>
        <el-checkbox v-model="savep">保存密码</el-checkbox>
        <el-button type="primary" @click="getToken" round>登录</el-button>
      </el-form-item>
      <el-form-item label="手动设置">
        <el-input placeholder="请输入token" @keyup.enter.native="setToken" v-model="token"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="setToken" round>提交token</el-button>
      </el-form-item>
    </el-form>
    <cDivider />
  </div>
</template>
<script>
import cDivider from "@/components/cDivider";
import InfoUser from "@/components/InfoUser";
import axios from "axios";
export default {
  name: "account",
  data() {
    return {
      username: null,
      password: null,
      token: null,
      savep: false, // 保存密码
      sharedState: { GLOBAL: this.GLOBAL }
    };
  },
  computed: {
    status() {
      if (
        this.GLOBAL.account.token == 0 ||
        typeof this.GLOBAL.account.token === "undefined" ||
        this.GLOBAL.account.token == null
      ) {
        return false;
      } else {
        return true;
      }
    }
  },
  methods: {
    upAccount() {
      this.GLOBAL.accountSave();
    },
    setToken() {
      this.GLOBAL.account = {
        token: this.token
      };
      this.upAccount();
    },
    getToken() {
      if (this.savep) {
        // 保存密码
        this.GLOBAL.config.smobile = this.username;
        this.GLOBAL.config.spass = this.password;
      } else {
        this.GLOBAL.config.smobile = 0;
        this.GLOBAL.config.spass = null;
      }
      this.GLOBAL.saveConfig();
      this.GLOBAL.getToken(this.username, this.password);
    },
    delToken() {
      this.$set(this.GLOBAL, "account", { toekn: "0" });
      this.GLOBAL.config.smobile = 0;
      this.GLOBAL.config.spass = null;
      this.GLOBAL.saveConfig();
      this.upAccount();
    },
    getPunch() {
      var req = {};
      // 请求 打卡
      axios({
        url: this.GLOBAL.api.checkin,
        method: "post",
        headers: new this.GLOBAL.headers(),
        data: req
      })
        .then(response => {
          this.upPunch(response.data);
        })
        .catch(e => {
          console.log(e);
        });
    },
    upPunch(res) {
      // 回调 打卡
      if (res.status == 1001006) {
        this.$message.warning(`${res.message}`);
      } else if (res.status == 200) {
        this.$message.success(
          `打卡成功, 连续打卡${res.content.days}天, 经验+${res.content.addExp}, 应援力+${res.content.addSupport}`
        );
      } else {
        this.$message.error(`${res.status}: ${res.message}`);
      }
      console.log(res, req);
    }
  },
  components: { cDivider, InfoUser }
};
</script>
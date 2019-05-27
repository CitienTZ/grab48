<style>
.c-readme {
  padding-left: 2rem;
  margin: 2rem 0;
}
.c-readme p span {
  margin-right: 1rem;
}
</style>

<template>
  <div>
    <h1># 关于&说明</h1>
    <cDivider/>
    <div class="c-readme">
      <!-- <img src="static/img/a.jpg" style="width: 80px"> -->
	  <p>好多项目都没了啊，所以我想起一句话：“长江后浪赶前浪，世上新人换旧人。”</p>
	  <p>你们这些啊，Excited！</p>
	  <p>那么人呐，就都不知道，自己就不可以预料。一个人的命运啊，当然要靠自我奋斗，但是也要考虑到历史的行程，我绝对不知道我作为一个，怎么就呢？</p>
	  <p>too young， too simple，sometimes naïve!</p>
	  <p>中国有一句话叫“闷声大发财”。我就什么话也不用说。这是最好的！</p>
	  <p>你们千万要注意啊，不要“见着风，是得雨”啊。接到这些消息，你本身也要判断，明白这意思吗？假使这些完全……无中生有的东西，你再帮他说一遍，你等于…这个东西…你...你也有责任的。</p>
	  <p>明白这个意思吧？</p>
	  <p>很惭愧，就做了一点微小的工作，谢谢大家。</p>
      <p>
        <span>当前版本: v{{ sharedState.version }}</span>
        <span>如有疑问请Telegram: <a href="https://t.me/kennen0">kennen0</a></span>
      </p>
      <p>
        <span>
          GitHub项目:
          <a target="_blank" href="http://github.com/kennen0/grab48">grab48</a>
        </span>
      </p>
      <cDivider/>
    </div>
    <cDivider/>
  </div>
</template>
<script>
import cDivider from "@/components/cDivider";
export default {
  name: "readme",
  data() {
    return {
      sharedState: this.GLOBAL,
    };
  },
  methods: {
  },
  mounted() {
  },
  components: { cDivider }
};
</script>
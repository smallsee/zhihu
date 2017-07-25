<template>
    <button
            class="btn btn-default"
            v-bind:class="{'btn-success': followed}"
            v-text="text"
            v-on:click="follow"
    >
    </button>
</template>

<script>
    export default {
        props: ['question'],
        mounted() {
            let _this = this;
            axios({
                method: 'post',
                url: '/api/question/follower',
                data: {
                    question: this.question
                }
            }).then(function (response) {
                _this.followed = response.data.followed;
            }).catch(function (error) {
                console.log(error);
            });
        },
        methods: {
          follow(){
              let _this = this;
              axios({
                  method: 'post',
                  url: '/api/question/follow',
                  data: {
                      question: this.question
                  }
              }).then(function (response) {
                  _this.followed = response.data.followed;
              }).catch(function (error) {
                  console.log(error);
              });
          }
        },
        data() {
            return {
                followed: false
            }
        },
        computed: {
            text() {
                return this.followed ? '已关注' : '关注该问题'
            }
        }
    }
</script>

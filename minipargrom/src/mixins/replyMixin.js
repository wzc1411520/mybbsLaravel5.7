import wepy from 'wepy'
// import util from '@/utils/util'
import api from '@/utils/api'
import Notify from '../components/vant/notify/notify';

export default class ReplyMixin extends wepy.mixin {
  config = {
    enablePullDownRefresh: true,
  }
  data = {
    // 回复数据
    repliesData: [],
    // 是否有更多数据
    noMoreData: false,
    // 是否在加载中
    isLoading: false,
    // 当前页数
    page: 1
  }
  // 获取话题回复
  async getReplies(reset = false) {
    try {
      // 请求话题回复接口
      let repliesResponse = await api.request({
        url: this.requestData.url,
        data: {
          page: this.page,
          include: this.requestData.include || 'user'
        }
      })

      if (repliesResponse.statusCode === 200) {
        let replies = repliesResponse.data.data

        // 获取当前用户
        let user = await this.$parent.getCurrentUser()
        replies.forEach((reply) => {
          // 控制是否可以删除
          reply.can_delete = this.canDelete(user, reply)
          // reply.created_at_diff = util.diffForHumans(reply.created_at)
        })

        // // 格式化回复创建时间
        // replies.forEach(function (reply) {
        //   reply.created_at_diff = util.diffForHumans(reply.created_at)
        // })
        // 如果reset不为true则合并 this.replies；否则直接覆盖
        this.repliesData = reset ? replies : this.repliesData.concat(replies)

        let pagination = repliesResponse.data.meta

        // 根据分页数据判断是否有更多数据
        if (pagination.current_page === pagination.last_page) {
          this.noMoreData = true
        }
        this.$apply()
      }

      return repliesResponse
    } catch (err) {
      console.log(err)
      Notify('服务器错误，请联系管理员');
      // wepy.showModal({
      //   title: '提示',
      //   content: '服务器错误，请联系管理员'
      // })
    }
  }

  canDelete(user, reply) {
    if (!user) {
      return false
    }

    return (reply.user.id === user.id)
  }

  methods = {
    // 删除回复
    async deleteReply(topicId, replyId) {
      // 确认是否删除
      let res = await wepy.showModal({
        title: '确认删除',
        content: '您确认删除该回复吗',
        confirmText: '删除',
        cancelText: '取消'
      })

      // 点击取消后返回
      if (!res.confirm) {
        return
      }
      try {
        // 调用接口删除回复
        let deleteResponse = await api.authRequest({
          url: 'topics/' + topicId + '/replies/' + replyId,
          method: 'DELETE'
        })

        // 删除成功
        if (deleteResponse.statusCode === 204) {
          Notify({
            text: '删除成功',
            backgroundColor: '#1989fa',
            duration:1000
          });
          // 将删除了的回复移除
          this.repliesData = this.repliesData.filter((reply) => reply.id !== replyId)
          this.reply_count = this.reply_count-1
          this.$apply()
        }

        return deleteResponse
      } catch (err) {
        console.log(err)
        Notify('服务器错误，请联系管理员');
      }
    }
  }

  async onPullDownRefresh() {
    this.noMoreData = false
    this.page = 1
    await this.getReplies(true)
    wepy.stopPullDownRefresh()
  }
  async onReachBottom () {
    // 如果没有更多数据，或者正在加载，直接返回
    if (this.noMoreData || this.isLoading) {
      return
    }
    // 设置为加载中
    this.isLoading = true
    this.page = this.page + 1
    await this.getReplies()
    this.isLoading = false
    this.$apply()
  }
}

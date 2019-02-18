import wepy from 'wepy'

export default class unreadCount extends wepy.mixin {
  data = {
    // 轮询
    interval: null,
    // 未读消息数
    unreadCount: 0
  }
  // 页面显示
  onShow() {
    this.updateUnreadCount()
    this.interval = setInterval(() => {
      this.updateUnreadCount()
    }, 30000)
  }
  // 页面隐藏
  onHide() {
    // 关闭轮询
    clearInterval(this.interval)
  }
  // 设置未读消息数
  updateUnreadCount() {
    // 从全局获取未读消息数
    this.unreadCount = this.$parent.globalData.Count.unread_count+this.$parent.globalData.Count.unreadTopicFavorite_count+this.$parent.globalData.Count.unreadReplyFavorite_count
    this.$apply()

    if (this.unreadCount) {
      // 设置 badge
      wepy.setTabBarBadge({
        index: 2,
        text: this.unreadCount.toString()
      })
    } else {
      // 移除 badge
      wepy.removeTabBarBadge({
        index: 2
      })
    }
  }
}

<div class="page-container" v-cloak>
    <el-card>
        <div slot="header" class="breadcrumb">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><a href="{:url('admin/main/index')}">首页</a></el-breadcrumb-item>
                <el-breadcrumb-item>配置列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div>
            <el-form size="small" :inline="true">
                <el-form-item>
                    <el-input v-model="where.keyword" placeholder="关键字"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-select v-model="where.setting_group" placeholder="分组">
                        <el-option label="不限" value=""></el-option>
                        <el-option v-for="(item,index) in setting_group" :key="index" :label="item"
                                   :value="item"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="searchEvent">查询</el-button>
                </el-form-item>
            </el-form>
        </div>
        <div>
            <el-table
                    size="small"
                    :data="data_list"
                    style="width: 100%">
                <el-table-column
                        prop="setting_id"
                        label="ID"
                        min-width="80">
                </el-table-column>
                <el-table-column
                        prop="setting_key"
                        label="key"
                        min-width="180">
                </el-table-column>
                <el-table-column
                        prop="setting_description"
                        min-width="200"
                        label="描述">
                </el-table-column>
                <el-table-column
                        prop="setting_group"
                        label="分组"
                        min-width="100">
                </el-table-column>
                <el-table-column
                        prop="type"
                        label="类型"
                        min-width="100">
                </el-table-column>
                <el-table-column
                        prop="updated_at"
                        label="更新时间"
                        min-width="140">
                </el-table-column>
                <el-table-column
                        align="center"
                        min-width="180"
                        label="操作">
                    <template slot-scope="{row}">
                        <el-link :href="`{:url('admin/setting/edit')}?setting_id=`+row.setting_id">
                            <el-button size="small" type="primary">编辑</el-button>
                        </el-link>
                        <el-link @click="deleteEvent">
                            <el-button size="small" type="danger">删除</el-button>
                        </el-link>
                    </template>
                </el-table-column>
            </el-table>
            <div class="pagination-container">
                <el-pagination
                        background
                        layout="prev, pager, next"
                        :total="total_num"
                        :current-page="current_page"
                        :page-size="per_page"
                        @current-change="currentChangeEvent"
                >
                </el-pagination>
            </div>
        </div>
    </el-card>
</div>

<script>
    $(function () {
        new Vue({
            el: ".page-container",
            data: {
                is_init_list: true,
                where: {},
                setting_group: [],
            },
            methods: {
                deleteEvent({setting_id}) {
                    this.$confirm("是否确认删除该记录？", '提示', {setting_id}).then(() => {
                        this.httpGet("{:url('admin/setting/delete')}", {}).then(res => {
                            if (res.status) {
                                this.$message.success(res.msg)
                            }
                        })
                    })
                },
                GetList() {
                    this.httpGet("{:url('admin/setting/index/lists')}", {
                        page: this.current_page,
                        ...this.where
                    }).then(res => {
                        let {lists = {}, setting_group = []} = res.data
                        this.setting_group = setting_group
                        this.handRes(lists)
                    })
                },
                searchEvent() {
                    this.GetList()
                }
            }
        })
    })
</script>
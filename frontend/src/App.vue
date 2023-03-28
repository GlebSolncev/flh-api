<template>
    <div class="common-layout">
        <el-container>
            <el-header>Freelancehunt Projects</el-header>
            <div v-if="empty">
                <el-alert title="warning alert" type="warning" />
            </div>
            <el-container v-else>
                <el-aside width="300px">
                    <div  class="content">
                        <apexcharts width="300" type="pie" :options="chartOptions" :series="series"></apexcharts>
                    </div><br>
                    <h3 style="text-align: center">Skills</h3>
                    <div v-for="skill in skills" style="width: auto">
                        <ul>
                            <li style="list-style-type: none ">
                                <a @click="filter(skill.id)">
                                    <span v-if="selected.includes(skill.id)" class="selected">{{ skill.title }}</span>
                                    <span v-else>{{ skill.title }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </el-aside>
                <el-main>
                    <div class="project-list" v-for="project in projects">
                        <h2>
                            <a style="color:green;text-decoration: none" v-bind:href="project.link" target="_blank">{{ project.title }}</a>
                            <el-button style="margin:5px; float: right" type="success" v-if="project.amount" plain round>
                                {{ project.amount }} {{ project.currency }}
                            </el-button>
                        </h2>
                        {{ project.description.substring(0, 150) + ((project.description.length > 150) ? '...' : '') }}<br>
                        <a :href="project.author.link" target="_blank" rel="noopener">
                            {{ project.author.last_name }}{{ project.author.first_name }} ({{project.author.username}})
                        </a><br>
                        <div v-for="skill in project.skills" style="display: inline">
                            <el-tag style="margin:5px;" class="ml-2" type="success">{{ skill.title }}</el-tag>
                        </div>
                        <div style="float: right"> {{project.published_at}}</div>
                    </div>

                    <el-pagination
                        :page-size="pageSize"
                        layout="total, prev, pager, next"
                        :total="totalPage"
                        @current-change="handleCurrentChange"
                    />
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>


<script>
import {ElConfigProvider} from 'element-plus'
import VueApexCharts from 'vue3-apexcharts'

export default {
    name: 'app',
    components: {
        ElConfigProvider,
        apexcharts: VueApexCharts,
    },
    data() {
        return {
            empty: false,
            status: false,
            response: {},
            skills: [],
            page: 1,
            projects: [],
            selected: [],
            pageSize: 0,
            totalPage: 0,

            loading: true,
            error: null,
            // chartOptions: [],
            series: [],
            chartOptions: {
                labels: ["< 500", "500-1000", "1000-5000", "> 5000"],
                dataLabels: {
                    enabled: true
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            positon: 'bottom',
                            show: false
                        }
                    }
                }],
            }
        }
    },
    async created() {
        await this.fetchProjects()
        await this.fetchSkills()
        await this.fetchBudget()

        this.loading = false
        // this.parcentLoading(100);
    },
    methods: {
        handleCurrentChange: async function(page){
            this.page = page
            await this.fetchProjects()
        },
        filter: async function (id) {
            if (this.selected.includes(id))
                this.selected.splice(this.selected.indexOf(id), 1)
            else
                this.selected.push(id)

            await this.fetchProjects()
            await this.fetchBudget(this.selected)
        },
        fetchProjects: async function () {
            const requestOptions = {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({skill_ids: this.selected})
            };

            var queryPage = ''
            if(this.page) queryPage = '?page=' + this.page

            const url = "http://localhost:3000/projects" + queryPage
            console.log(url)
            const response = await fetch(url, requestOptions);
            const data = await response.json();

            if (data.payload) {
                this.status = data.status
                if(!data.payload.data.length){
                    this.empty = true
                    this.loading = false
                }
                this.projects = data.payload.data
                this.pageSize = 25
                this.totalPage = data.payload.total
            }
            // this.parcentLoading(30)
        },
        fetchSkills: async function () {
            var res = [];

            await fetch("http://localhost:3000/skills")
                .then(response => response.json())
                .then(data => (res = data));

            if (res.status)
                this.skills = res.payload
            // this.parcentLoading(30);
        },
        fetchBudget: async function (skillIds) {
            const requestOptions = {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({skill_ids: skillIds})
            };

            const url = "http://localhost:3000/projects/budgets"
            const response = await fetch(url, requestOptions);
            const data = await response.json();

            if (data.status){
                this.series = data.payload
            }
        },
    }
}
</script>

<style>
.project-list {
    padding: 3px
}

.project-list:hover {
    background: #2c3e50;
    padding: 3px
}

a > span {
    cursor: pointer;
}

span.selected {
    cursor: pointer;
    color: green;
}

.el-row {
    margin-bottom: 20px;
}

.el-row:last-child {
    margin-bottom: 0;
}

.el-col {
    border-radius: 4px;
}

.grid-content {
    border-radius: 4px;
    min-height: 36px;
}
</style>
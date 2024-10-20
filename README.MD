Сформировать запросы в SQL по сбору статистики (количество проектов, сотрудников, средний возраст сотрудников и т.д.)

* Количество проектов

select count(*) as project_count from project p;

* Количество сотрудников

select count(*) as employees_count from employee e;

* средний возраст сотрудников

select sum(e.age) / count(*)  as avg_employees_age from employee e;


* средний возраст работников на каждом проекте и среднее кол-во работников на каждом проекте

with emp_pro as (
    select ep.employee_id, ep.project_id from project p JOIN employees_projects ep ON p.id = ep.project_id
)
select
emp_pro.project_id,
sum(e.age) / count(e.*) as avg_project_emplpyees_age,
count(e.*) as project_employees_count
from emp_pro
JOIN employee e
ON emp_pro.employee_id = e.id
GROUP BY  emp_pro.project_id
ORDER BY  emp_pro.project_id


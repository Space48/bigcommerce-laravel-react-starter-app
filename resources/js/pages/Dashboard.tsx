import React from 'react';
import {PageBody, PageHeader} from "../components";
import {Space48Apps} from "../components";

const Dashboard = () => {
  return (
    <>
      <PageHeader title="Dashboard" />
      <PageBody>
        <Space48Apps />
      </PageBody>
    </>
  );
}

export default Dashboard;

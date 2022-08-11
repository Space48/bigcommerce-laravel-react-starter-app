import React from 'react';
import {Text} from "@bigcommerce/big-design";
import {PageHeader} from "../components";

const FourOhFour = () => {
  return (
    <>
      <PageHeader title="O oh, page not found" backLinkText="Back">
        <Text>Sorry about that.</Text>
      </PageHeader>
    </>
  );
}

export default FourOhFour;

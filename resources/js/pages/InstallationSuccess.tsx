import React, {useContext} from 'react';
import styled from "styled-components";
import {Box, Flex, FlexItem, H1, H4} from "@bigcommerce/big-design";
import {CheckCircleIcon} from "@bigcommerce/big-design-icons";
import ConfigContext from "../context/ConfigContext";
import {useParams} from "react-router-dom";

const Container = styled(Box)`
  margin: 3rem auto;
  text-align: center;

  h1 {
    margin: 4rem 0;
  }
`;

const Img = styled.img`
  max-width: 100%;
`;

const InstallationSuccess = () => {
  const context = useContext(ConfigContext);
  const {store_hash} = useParams();
  const redirectTo = store_hash ? `/stores/${store_hash}` : '/';

  return (
    <Container>
      <Img src='/img/logo-300x111.png' alt={`${context?.appName} Logo`}/>
      <Flex flexDirection="row" alignItems="center" justifyContent="center" marginVertical="large">
        <FlexItem>
          <CheckCircleIcon color="success"/>
        </FlexItem>
        <FlexItem>
          <H4 margin="none" marginLeft="xSmall">Installation success</H4>
        </FlexItem>
      </Flex>

      <H1 marginVertical="xxxLarge">Thanks for choosing {context?.appName}</H1>
    </Container>
  );
}

export default InstallationSuccess;

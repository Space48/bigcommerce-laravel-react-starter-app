import React, {useContext} from 'react';
import styled from "styled-components";
import {Box, H1} from "@bigcommerce/big-design";
import ConfigContext from "../context/ConfigContext";
import {useParams} from "react-router-dom";
import {HookParams} from "../types";

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

const WelcomeNewUser = () => {
  const context = useContext(ConfigContext);
  const {store_hash} = useParams();
  const redirectTo = store_hash ? `/stores/${store_hash}/categories` : '/';

  return (
    <Container>
      <Img src='/img/logo-300x111.png' alt={`${context?.appName} Logo`}/>
      <H1>Welcome to {context?.appName}</H1>
    </Container>
  );
}

export default WelcomeNewUser;

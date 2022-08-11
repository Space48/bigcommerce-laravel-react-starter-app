import React, {useContext} from 'react';
import styled from 'styled-components';
import {Box, Button, H1} from '@bigcommerce/big-design';
import ConfigContext from '../context/ConfigContext';
import {useParams, useHistory} from 'react-router-dom';

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

const FormContainer = styled.div`
  display: inline-block;

  button {
    display: block;
    margin: 2rem auto;
    width: 10rem;
  }

  div {
    display: inline-flex;
  }
`;

const WelcomeNewUser = () => {
  const context = useContext(ConfigContext);
  const {store_hash} = useParams();
  const history = useHistory();
  return (
    <Container>
      <Img src='/img/logo-300x111.png' alt={`${context?.appName} Logo`}/>
      <H1>Welcome to {context?.appName}</H1>

      <FormContainer>
        <Button onClick={() => history.push(`/stores/${store_hash}`)}>Go to Dashboard</Button>
      </FormContainer>
    </Container>
  );
}

export default WelcomeNewUser;

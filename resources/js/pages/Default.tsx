import React, {useContext} from 'react';
import styled from 'styled-components';
import {Box, Button, H1, Text} from '@bigcommerce/big-design';
import ConfigContext from '../context/ConfigContext';
import {useHistory} from 'react-router-dom';
import useSWR from 'swr';

const Container = styled(Box)`
  margin: 3rem auto;
  text-align: center;

  h1 {
    margin: 4rem 0 0.5em;
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

const Default = () => {
  const context = useContext(ConfigContext);
  const history = useHistory();

  const {data: userResponse} = useSWR(`/api/users/me`, null);

  return (
    <Container>
      <Img src='/img/logo-300x111.png' alt={`${context?.appName} Logo`}/>
      <H1>{context?.appName}</H1>
      <Text bold>{context?.appDescription}</Text>
      <FormContainer>
        {
          userResponse?.data ?
            <Button onClick={() => history.push('/stores')}>View stores</Button>
            :
            <Button
              onClick={() => window.open('https://login.bigcommerce.com/deep-links/marketplace/apps/' + context?.appId)}
            >
              Open
            </Button>
        }
      </FormContainer>

    </Container>
  );
}

export default Default;

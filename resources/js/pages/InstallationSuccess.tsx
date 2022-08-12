import React, {useContext} from 'react';
import styled from 'styled-components';
import {Box, Button, Flex, FlexItem, H1, H4} from '@bigcommerce/big-design';
import {CheckCircleIcon} from '@bigcommerce/big-design-icons';
import ConfigContext from '../context/ConfigContext';
import {useHistory, useParams} from 'react-router-dom';

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

const InstallationSuccess = () => {
  const context = useContext(ConfigContext);
  const {store_hash} = useParams();
  const history = useHistory();

  return (
    <Container>
      <Img src='/img/logo.png' alt={`${context?.appName} Logo`}/>
      <Flex flexDirection="row" alignItems="center" justifyContent="center" marginVertical="large">
        <FlexItem>
          <CheckCircleIcon color="success"/>
        </FlexItem>
        <FlexItem>
          <H4 margin="none" marginLeft="xSmall">Installation success</H4>
        </FlexItem>
      </Flex>

      <H1 marginVertical="xxxLarge">Thanks for choosing {context?.appName}</H1>

      <FormContainer>
        <Button onClick={() => history.push(`/stores/${store_hash}`)}>Go to Dashboard</Button>
      </FormContainer>
    </Container>
  );
}

export default InstallationSuccess;

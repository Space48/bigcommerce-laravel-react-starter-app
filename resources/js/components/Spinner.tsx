import React from 'react';
import {Flex, FlexItem, ProgressCircle} from '@bigcommerce/big-design';

interface Props {
  size?: any;
}

const Spinner = ({size = 'large'}: Props) => {
  return (
    <Flex marginVertical={size} justifyContent="center">
      <FlexItem alignSelf="center">
        <ProgressCircle size={size}/>
      </FlexItem>
    </Flex>
  )
}

export default Spinner;

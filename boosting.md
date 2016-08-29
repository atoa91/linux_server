#Boosting (Adaboost)

### Boosting이란?
Weak classifier들을 Ensemble하여 Strong classifier로 만드는 것을 뜻한다. Weak classifier란 낮은 정확도를 가지는 분류기를 뜻한다.
Ensemble method는 다수의 classifier가 각자 Voting을 통해 결정을 내리는 방법이라고 생각하면 쉽다.
Voting은 각 classifier에게 Weight(가중치)를 부여한 후 해당 데이터 셋에 대하여 분류할 때 각각의 classifier들이 분류한 결과에 가중치를 더해서
가장 가중치가 큰 분류로 최종 결정을 내린다.

<그림> 해당 영역을 어떻게 나타낼 것인가?

### Weight/Distribution
Voting에 사용되는 Weight는 어떤 방식으로 주어져야할까? 간단하다. Error rate가 높을수록 가중치를 적게 주고 낮을수록 가중치를 많이준다.
그러므로 Error rate이 낮을수록 Voting에 영향력을 더 발휘할 수 있다.
Boosting에서의 Error rate은 Distribution이라는 방법을 통해서 결정된다. 밑에서 알고리즘 식을보며 자세하게 다룰 것이지만 간단히 이야기하면
예측한 값과 실제 값이 다른(틀린 prediction을 한) 데이터의 Distribution들의 합이 Error rate(0 < E < 0.5)가 된다.
왜 Error rate이 0 < E < 0.5의 수치를 가질까 그 이유는 Distribution의 특징에 있다.
Distribution의 총합은 항상 1을 가진다. 왜냐하면 Boosting의 특징상 같은 머신러닝 방법으로 같은 데이터 셋을 반복적으로 분류한다.
이 과정에서 분류한 모델들에게 Weight를 부여하기 위해서는 Error를 측정하기위한 방법, 정도가 일정해야한다. 
따라서 여기에 사용되는 개념이 Distribution 이다. Distribution에 대해 더 설명하기 전에 한가지의 의문을 해결하고자 한다.

Boosting이 Weak classifier로 Strong classifier를 만드는 것이라면 단순히 같은 머신러닝 기법과 같은 데이터로 여러 모델을 만들고 그들끼리
Voting(Ensemble)한다고 Strong classifier가 만들어질 것인가? 만들어질 수 없다.
그렇기 때문에 Boosting은 같은 기법으로 같은 데이터를 계속 분류하는 것을 반복하는 것이 아니라. 
####처음 분류한 모델을 가지고 Distribution을 재 분배 하여 다른 데이터 셋을 만든다.

<그림> + - 가 커지는 그림

Boosting은 같은 기법으로 분류기를 만들지만 데이터 셋은 Distribution으로 조정된 새로운 데이터 셋이 사용된다. 
이를 통해 Error rate이 결정되고 Voting에 영향을 줄 수 있는 지표인 Weight가 각각의 분류기에 분배된다. 
Distribution은 초기에는 n개의 데이터가 있으면 1/n으로 고르게 분포된다. 이후 한번 분류된 결과에 따라서 
- 예측한 값과 실제 값이 다른(틀린 prediction을 한) 경우 Distribution의 값을 증가시킨다.
- 예측한 값과 실제 값이 같은(맞는 prediction을 한) 경우 Distribution의 값을 감소시킨다.
그림에서 볼 수 있듯이 틀릴 경우 해당 데이터 셋의 Distribution이 커져서 Error rate를 감소시키기 위해
Distribution이 큰 데이터를 중심으로 모델링이 일어날 것이다.

###여기까지 개념정리
알고리즘 식에 들어가기 앞서서 이때까지 개념을 정리해보면
1. Boosting은 같은 기법을 사용해서 Distribution이 적용된 새로운 데이터 셋을 predict하여 매번 새로운 모델을 만든다.(Weak classifiers)
1-1 Distribution은 초기값은 모두 동등하게 1/n으로 부여되며 이후 맞은 경우 Distribution을 감소시키고 틀린 경우 Distribution을 증가시킨다.
1-2 1-1을 반복하며 데이터에게 주어진 Distribution이 매번 바뀌고 이를 통해 모델의 Error rate(틀린 데이터의 Distribution의 합)를 결정한다.
2. 만들어진 모델들은 Error rate에 따라 Voting에 참여할 수 있는 Wegiht라는 영향력을 부여받는다.
3. 부여된 Weight를 가지고 Voting을 실시(Ensemble method) Strong classifier로 거듭난다.

<그림>

###Adaboost 알고리즘
식
- 초기설정

- 반복

-- Update Distribution

--- Distribution normalization

-- Weight

--- Weight & Error rate graph

- Voting(Ensemble method)

Boosting(Adaboost)의 한계점
Boosting의 매력은 맞을 수록 Distribution이 감소하는 것 틀릴 수록 Distribution이 증가하는 단순한 방식으로 기존의 모델보다
성능이 뛰어난 모델을 만들 수 있다는 것이다. 하지만 반복 횟수가 늘어날 수록 Training 데이터 셋에 Overfitting되는 문제가 발생한다.
특히 Outlier가 존재할 경우 반복 횟수가 늘어날 수록 Outlier의 Distribution은 지속적으로 증가할 것이며 Error rate을 낮추기 위해서
Outlier를 기준으로 모델링 될 확률이 높다.
<그림> Overfitting의 함정


참고 MIT OpenCourseWave about " Leraning : Boosting"
     https://www.youtube.com/watch?v=UHBmv7qCey4
그림자료



